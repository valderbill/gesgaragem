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
-- Data for Name: veiculos; Type: TABLE DATA; Schema: public; Owner: valdeli
--

COPY public.veiculos (id, placa, modelo, tipo, motorista_id, acesso_id, created_at, updated_at, acesso_liberado_id) FROM stdin;
\.


--
-- Data for Name: registro_veiculos; Type: TABLE DATA; Schema: public; Owner: valdeli
--

COPY public.registro_veiculos (id, veiculo_id, estacionamento_id, hora_entrada, hora_saida, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: ocorrencias; Type: TABLE DATA; Schema: public; Owner: valdeli
--

COPY public.ocorrencias (id, registro_id, descricao, data, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: valdeli
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
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
-- PostgreSQL database dump complete
--

