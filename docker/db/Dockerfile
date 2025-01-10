FROM postgres:16.1

COPY ../../sql/03-toubee_auth.data.sql /docker-entrypoint-initdb.d/02-data.sql
COPY ../../sql/toubee_auth.schema.sql /docker-entrypoint-initdb.d/01-schema.sql
