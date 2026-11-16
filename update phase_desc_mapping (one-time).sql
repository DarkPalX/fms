;WITH UserDept AS (
    SELECT 
        LTRIM(RTRIM(value)) AS dept_name
    FROM [PMC-WFS].dbo.users u
    CROSS APPLY STRING_SPLIT(CAST(u.department AS VARCHAR(4000)), '|')
),
DistinctDept AS (
    SELECT DISTINCT dept_name
    FROM UserDept
    WHERE dept_name IS NOT NULL AND dept_name <> ''
),
ScoredMatch AS (
    SELECT 
        f.id AS fms_id,
        f.phase_code,
        f.phase_desc,
        d.dept_name,
        
        -- soundex similarity score
        DIFFERENCE(f.phase_desc, d.dept_name) AS score_soundex,

        -- direct LIKE similarity score
        CASE 
            WHEN f.phase_desc LIKE '%' + d.dept_name + '%' THEN 1 
            ELSE 0 
        END AS score_like
    FROM fms_db.dbo.fms_phase_code f
    CROSS JOIN DistinctDept d
),
Ranked AS (
    SELECT *,
        ROW_NUMBER() OVER (
            PARTITION BY fms_id 
            ORDER BY score_like DESC, score_soundex DESC
        ) AS rn
    FROM ScoredMatch
)
-- Update statement
UPDATE f
SET f.phase_desc_mapping = r.dept_name
FROM fms_db.dbo.fms_phase_code f
JOIN Ranked r
    ON f.id = r.fms_id
WHERE r.rn = 1;
