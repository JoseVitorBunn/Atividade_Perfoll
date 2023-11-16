# Atividade_Perfoll
# Aluno: Jose Vitor Bunn - Atividade Quinta

CREATE TABLE IF NOT EXISTS public.leituras_sinais_vitais
(
    paciente_id integer DEFAULT nextval('paciente_id_seq'::regclass),
    frequencia_cardiaca numeric(5,2),
    pressao_arterial numeric(6,2),
    temperatura numeric(4,2),
    saturacao_oxigenio numeric(5,2),
    data_hora_leitura timestamp without time zone,
    nome character varying(255) COLLATE pg_catalog."default"
)

CREATE TABLE IF NOT EXISTS public.pacientes
(
    id integer NOT NULL DEFAULT nextval('pacientes_id_seq'::regclass),
    nome character varying(255) COLLATE pg_catalog."default",
    sexo character varying(10) COLLATE pg_catalog."default",
    idade integer,
    cidade character varying(255) COLLATE pg_catalog."default",
    CONSTRAINT pacientes_pkey PRIMARY KEY (id)
)
