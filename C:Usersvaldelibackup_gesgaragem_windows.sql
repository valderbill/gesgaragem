--
-- PostgreSQL database dump
--

-- Dumped from database version 14.18 (Ubuntu 14.18-0ubuntu0.22.04.1)
-- Dumped by pg_dump version 14.18 (Ubuntu 14.18-0ubuntu0.22.04.1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: tipo_veiculo; Type: TYPE; Schema: public; Owner: valdeli
--

CREATE TYPE public.tipo_veiculo AS ENUM (
    'OFICIAL',
    'VISITANTE',
    'TERCEIRO'
);


ALTER TYPE public.tipo_veiculo OWNER TO valdeli;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: acessos_liberados; Type: TABLE; Schema: public; Owner: valdeli
--

CREATE TABLE public.acessos_liberados (
    id integer NOT NULL,
    nome character varying(255) NOT NULL,
    matricula character varying(255) NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE public.acessos_liberados OWNER TO valdeli;

--
-- Name: acessos_liberados_id_seq; Type: SEQUENCE; Schema: public; Owner: valdeli
--

CREATE SEQUENCE public.acessos_liberados_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.acessos_liberados_id_seq OWNER TO valdeli;

--
-- Name: acessos_liberados_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: valdeli
--

ALTER SEQUENCE public.acessos_liberados_id_seq OWNED BY public.acessos_liberados.id;


--
-- Name: cache; Type: TABLE; Schema: public; Owner: valdeli
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache OWNER TO valdeli;

--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: valdeli
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache_locks OWNER TO valdeli;

--
-- Name: estacionamentos; Type: TABLE; Schema: public; Owner: valdeli
--

CREATE TABLE public.estacionamentos (
    id integer NOT NULL,
    capacidade integer NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE public.estacionamentos OWNER TO valdeli;

--
-- Name: estacionamentos_id_seq; Type: SEQUENCE; Schema: public; Owner: valdeli
--

CREATE SEQUENCE public.estacionamentos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.estacionamentos_id_seq OWNER TO valdeli;

--
-- Name: estacionamentos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: valdeli
--

ALTER SEQUENCE public.estacionamentos_id_seq OWNED BY public.estacionamentos.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: valdeli
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO valdeli;

--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: valdeli
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.migrations_id_seq OWNER TO valdeli;

--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: valdeli
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: motoristas_oficiais; Type: TABLE; Schema: public; Owner: valdeli
--

CREATE TABLE public.motoristas_oficiais (
    id integer NOT NULL,
    nome character varying(255) NOT NULL,
    cpf character varying(14) NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE public.motoristas_oficiais OWNER TO valdeli;

--
-- Name: motoristas_oficiais_id_seq; Type: SEQUENCE; Schema: public; Owner: valdeli
--

CREATE SEQUENCE public.motoristas_oficiais_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.motoristas_oficiais_id_seq OWNER TO valdeli;

--
-- Name: motoristas_oficiais_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: valdeli
--

ALTER SEQUENCE public.motoristas_oficiais_id_seq OWNED BY public.motoristas_oficiais.id;


--
-- Name: ocorrencias; Type: TABLE; Schema: public; Owner: valdeli
--

CREATE TABLE public.ocorrencias (
    id integer NOT NULL,
    registro_id integer,
    descricao text,
    data timestamp without time zone,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE public.ocorrencias OWNER TO valdeli;

--
-- Name: ocorrencias_id_seq; Type: SEQUENCE; Schema: public; Owner: valdeli
--

CREATE SEQUENCE public.ocorrencias_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ocorrencias_id_seq OWNER TO valdeli;

--
-- Name: ocorrencias_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: valdeli
--

ALTER SEQUENCE public.ocorrencias_id_seq OWNED BY public.ocorrencias.id;


--
-- Name: registro_veiculos; Type: TABLE; Schema: public; Owner: valdeli
--

CREATE TABLE public.registro_veiculos (
    id integer NOT NULL,
    veiculo_id integer,
    estacionamento_id integer,
    hora_entrada timestamp without time zone,
    hora_saida timestamp without time zone,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE public.registro_veiculos OWNER TO valdeli;

--
-- Name: registro_veiculos_id_seq; Type: SEQUENCE; Schema: public; Owner: valdeli
--

CREATE SEQUENCE public.registro_veiculos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.registro_veiculos_id_seq OWNER TO valdeli;

--
-- Name: registro_veiculos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: valdeli
--

ALTER SEQUENCE public.registro_veiculos_id_seq OWNED BY public.registro_veiculos.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: valdeli
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


ALTER TABLE public.sessions OWNER TO valdeli;

--
-- Name: veiculos; Type: TABLE; Schema: public; Owner: valdeli
--

CREATE TABLE public.veiculos (
    id integer NOT NULL,
    placa character varying(10) NOT NULL,
    modelo character varying(100),
    tipo public.tipo_veiculo NOT NULL,
    motorista_id integer,
    acesso_id integer,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone,
    acesso_liberado_id bigint
);


ALTER TABLE public.veiculos OWNER TO valdeli;

--
-- Name: veiculos_id_seq; Type: SEQUENCE; Schema: public; Owner: valdeli
--

CREATE SEQUENCE public.veiculos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.veiculos_id_seq OWNER TO valdeli;

--
-- Name: veiculos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: valdeli
--

ALTER SEQUENCE public.veiculos_id_seq OWNED BY public.veiculos.id;


--
-- Name: acessos_liberados id; Type: DEFAULT; Schema: public; Owner: valdeli
--

ALTER TABLE ONLY public.acessos_liberados ALTER COLUMN id SET DEFAULT nextval('public.acessos_liberados_id_seq'::regclass);


--
-- Name: estacionamentos id; Type: DEFAULT; Schema: public; Owner: valdeli
--

ALTER TABLE ONLY public.estacionamentos ALTER COLUMN id SET DEFAULT nextval('public.estacionamentos_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: valdeli
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: motoristas_oficiais id; Type: DEFAULT; Schema: public; Owner: valdeli
--

ALTER TABLE ONLY public.motoristas_oficiais ALTER COLUMN id SET DEFAULT nextval('public.motoristas_oficiais_id_seq'::regclass);


--
-- Name: ocorrencias id; Type: DEFAULT; Schema: public; Owner: valdeli
--

ALTER TABLE ONLY public.ocorrencias ALTER COLUMN id SET DEFAULT nextval('public.ocorrencias_id_seq'::regclass);


--
-- Name: registro_veiculos id; Type: DEFAULT; Schema: public; Owner: valdeli
--

ALTER TABLE ONLY public.registro_veiculos ALTER COLUMN id SET DEFAULT nextval('public.registro_veiculos_id_seq'::regclass);


--
-- Name: veiculos id; Type: DEFAULT; Schema: public; Owner: valdeli
--

ALTER TABLE ONLY public.veiculos ALTER COLUMN id SET DEFAULT nextval('public.veiculos_id_seq'::regclass);


--
-- Data for Name: acessos_liberados; Type: TABLE DATA; Schema: public; Owner: valdeli
--

COPY public.acessos_liberados (id, nome, matricula, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: valdeli
--

COPY public.cache (key, value, expiration) FROM stdin;
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: valdeli
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- Data for Name: estacionamentos; Type: TABLE DATA; Schema: public; Owner: valdeli
--

COPY public.estacionamentos (id, capacidade, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: valdeli
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	2025_06_13_200024_create_sessions_table	1
2	2025_06_13_200623_create_cache_table	2
\.


--
-- Data for Name: motoristas_oficiais; Type: TABLE DATA; Schema: public; Owner: valdeli
--

COPY public.motoristas_oficiais (id, nome, cpf, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: ocorrencias; Type: TABLE DATA; Schema: public; Owner: valdeli
--

COPY public.ocorrencias (id, registro_id, descricao, data, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: registro_veiculos; Type: TABLE DATA; Schema: public; Owner: valdeli
--

COPY public.registro_veiculos (id, veiculo_id, estacionamento_id, hora_entrada, hora_saida, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: valdeli
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
\.


--
-- Data for Name: veiculos; Type: TABLE DATA; Schema: public; Owner: valdeli
--

COPY public.veiculos (id, placa, modelo, tipo, motorista_id, acesso_id, created_at, updated_at, acesso_liberado_id) FROM stdin;
\.


--
-- Name: acessos_liberados_id_seq; Type: SEQUENCE SET; Schema: public; Owner: valdeli
--

SELECT pg_catalog.setval('public.acessos_liberados_id_seq', 1, false);


--
-- Name: estacionamentos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: valdeli
--

SELECT pg_catalog.setval('public.estacionamentos_id_seq', 1, false);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: valdeli
--

SELECT pg_catalog.setval('public.migrations_id_seq', 2, true);


--
-- Name: motoristas_oficiais_id_seq; Type: SEQUENCE SET; Schema: public; Owner: valdeli
--

SELECT pg_catalog.setval('public.motoristas_oficiais_id_seq', 1, false);


--
-- Name: ocorrencias_id_seq; Type: SEQUENCE SET; Schema: public; Owner: valdeli
--

SELECT pg_catalog.setval('public.ocorrencias_id_seq', 1, false);


--
-- Name: registro_veiculos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: valdeli
--

SELECT pg_catalog.setval('public.registro_veiculos_id_seq', 1, false);


--
-- Name: veiculos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: valdeli
--

SELECT pg_catalog.setval('public.veiculos_id_seq', 1, false);


--
-- Name: acessos_liberados acessos_liberados_pkey; Type: CONSTRAINT; Schema: public; Owner: valdeli
--

ALTER TABLE ONLY public.acessos_liberados
    ADD CONSTRAINT acessos_liberados_pkey PRIMARY KEY (id);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: valdeli
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: valdeli
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: estacionamentos estacionamentos_pkey; Type: CONSTRAINT; Schema: public; Owner: valdeli
--

ALTER TABLE ONLY public.estacionamentos
    ADD CONSTRAINT estacionamentos_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: valdeli
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: motoristas_oficiais motoristas_oficiais_pkey; Type: CONSTRAINT; Schema: public; Owner: valdeli
--

ALTER TABLE ONLY public.motoristas_oficiais
    ADD CONSTRAINT motoristas_oficiais_pkey PRIMARY KEY (id);


--
-- Name: ocorrencias ocorrencias_pkey; Type: CONSTRAINT; Schema: public; Owner: valdeli
--

ALTER TABLE ONLY public.ocorrencias
    ADD CONSTRAINT ocorrencias_pkey PRIMARY KEY (id);


--
-- Name: registro_veiculos registro_veiculos_pkey; Type: CONSTRAINT; Schema: public; Owner: valdeli
--

ALTER TABLE ONLY public.registro_veiculos
    ADD CONSTRAINT registro_veiculos_pkey PRIMARY KEY (id);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: valdeli
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: veiculos veiculos_pkey; Type: CONSTRAINT; Schema: public; Owner: valdeli
--

ALTER TABLE ONLY public.veiculos
    ADD CONSTRAINT veiculos_pkey PRIMARY KEY (id);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: valdeli
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: valdeli
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: veiculos fk_veiculos_acesso_liberado; Type: FK CONSTRAINT; Schema: public; Owner: valdeli
--

ALTER TABLE ONLY public.veiculos
    ADD CONSTRAINT fk_veiculos_acesso_liberado FOREIGN KEY (acesso_liberado_id) REFERENCES public.acessos_liberados(id) ON DELETE SET NULL;


--
-- Name: ocorrencias ocorrencias_registro_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: valdeli
--

ALTER TABLE ONLY public.ocorrencias
    ADD CONSTRAINT ocorrencias_registro_id_fkey FOREIGN KEY (registro_id) REFERENCES public.registro_veiculos(id);


--
-- Name: registro_veiculos registro_veiculos_estacionamento_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: valdeli
--

ALTER TABLE ONLY public.registro_veiculos
    ADD CONSTRAINT registro_veiculos_estacionamento_id_fkey FOREIGN KEY (estacionamento_id) REFERENCES public.estacionamentos(id);


--
-- Name: registro_veiculos registro_veiculos_veiculo_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: valdeli
--

ALTER TABLE ONLY public.registro_veiculos
    ADD CONSTRAINT registro_veiculos_veiculo_id_fkey FOREIGN KEY (veiculo_id) REFERENCES public.veiculos(id);


--
-- Name: veiculos veiculos_acesso_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: valdeli
--

ALTER TABLE ONLY public.veiculos
    ADD CONSTRAINT veiculos_acesso_id_fkey FOREIGN KEY (acesso_id) REFERENCES public.acessos_liberados(id);


--
-- Name: veiculos veiculos_motorista_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: valdeli
--

ALTER TABLE ONLY public.veiculos
    ADD CONSTRAINT veiculos_motorista_id_fkey FOREIGN KEY (motorista_id) REFERENCES public.motoristas_oficiais(id);


--
-- PostgreSQL database dump complete
--

