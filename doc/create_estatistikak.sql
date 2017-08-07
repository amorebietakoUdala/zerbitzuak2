DROP TABLE estatistikak;

CREATE TABLE estatistikak (id int not null auto_increment, primary key (id)) as 
SELECT enpresa_id, year(noiz) as urtea, count(*) as eskakizunak
FROM eskakizunak es
group by enpresa_id, urtea
order by urtea desc;