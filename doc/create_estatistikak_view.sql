DROP TABLE estatistikak;

DROP VIEW estatistikak;

CREATE OR REPLACE VIEW estatistikak as 
SELECT enpresa_id, year(noiz) as urtea, count(*) as eskakizunak
FROM eskakizunak
group by enpresa_id, urtea
order by urtea desc;