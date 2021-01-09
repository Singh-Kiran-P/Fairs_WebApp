--
-- PostgreSQL database dump
--

-- Dumped from database version 11.9 (Debian 11.9-0+deb10u1)
-- Dumped by pg_dump version 11.9 (Debian 11.9-0+deb10u1)

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
-- Data for Name: accounts; Type: TABLE DATA; Schema: public; Owner: kiransingh
--

INSERT INTO public.accounts VALUES (7, 'admin', 'admin', '$2y$10$oZNE3wZeACgmkMEStxRHgOtSYIfQl9WTqdThEBF/0XNGpZrIKHybu', 'multajomlu@nedoz.com', 'admin', '2021-01-06 17:35:52.586744', true, '$2y$10$qAONCPDtJHXUYbyP68MXLepoRDxg0VTIc2P7k3CQ7HIoDSz7HDZwK');
INSERT INTO public.accounts VALUES (5, 'Hasselt', 'hasselt', '$2y$10$SM0kObjTmQwqQurAsviMgOv346xs0LkQDWUJz5H5f/6gsExHHtGta', 'singh.kiran2456@gmail.com', 'city', '2021-01-06 16:00:47.931629', true, '$2y$10$i4Ac0gGuesag.mzZmM8VnesSvKb6K9CtMjM2EetqBLRjnQQW90/ni');
INSERT INTO public.accounts VALUES (8, 'Tongeren', 'tongeren', '$2y$10$5IWKGL/YqtjxDUFjkproaetDfZHGNVHjysb6sd8bBZBX3w7.PKDFq', 'cebeyoc189@28woman.com', 'city', '2021-01-06 17:59:37.667423', true, '$2y$10$LyiBAej7ONR.wpsti52UmeB1oQWHvfg0v4UnWxU4valPn4niu0F/a');
INSERT INTO public.accounts VALUES (9, 'Visitor1', 'v1', '$2y$10$qI.ByzbH48CToDQEf2q9sOiRgmdDSPUVVf.96ZZ4ct4FSygJC9emK', 'singh.kiran2456@hotmail.com', 'visitor', '2021-01-06 18:08:26.540616', true, '$2y$10$ytl/jmQ7WkeUUb.MAX/lgOeL6w7kPPdCJmkLlZmW8fFseDpanthTa');
INSERT INTO public.accounts VALUES (12, 'Visitor2', 'v2', '$2y$10$ilBnU3FYxVziKhT9s3qIGustyE9lXH6dRBmi6FQ7CX1mkELuiRe8S', 'kiran.singh@student.uhasselt.be', 'visitor', '2021-01-06 18:12:12.33924', true, '$2y$10$b/S8bOa3Iq61VDNhx1GpC.kCC4nb4mRU1x8s4QZLuSksTlxa96p9m');


--
-- Data for Name: city; Type: TABLE DATA; Schema: public; Owner: kiransingh
--

INSERT INTO public.city VALUES (1, 5, '0117896416', 'Lorem Ipsum is slechts een proeftekst uit het drukkerij- en zetterijwezen. Lorem Ipsum is de standaard proeftekst in deze bedrijfstak sinds de 16e eeuw, toen een onbekende drukker een zethaak met letters nam en ze door elkaar husselde om een font-catalogus te maken. Het heeft niet alleen vijf eeuwen overleefd maar is ook, vrijwel onveranderd, overgenomen in elektronische letterzetting. Het is in de jaren ''60 populair geworden met de introductie van Letraset vellen met Lorem Ipsum passages en meer recentelijk door desktop publishing software zoals Aldus PageMaker die versies van Lorem Ipsum bevatten.');
INSERT INTO public.city VALUES (3, 8, '01158586149', 'Lorem Ipsum is slechts een proeftekst uit het drukkerij- en zetterijwezen. Lorem Ipsum is de standaard proeftekst in deze bedrijfstak sinds de 16e eeuw, toen een onbekende drukker een zethaak met letters nam en ze door elkaar husselde om een font-catalogus te maken. Het heeft niet alleen vijf eeuwen overleefd maar is ook, vrijwel onveranderd, overgenomen in elektronische letterzetting. Het is in de jaren ''60 populair geworden met de introductie van Letraset vellen met Lorem Ipsum passages en meer recentelijk door desktop publishing software zoals Aldus PageMaker die versies van Lorem Ipsum bevatten.');


--
-- Data for Name: fair; Type: TABLE DATA; Schema: public; Owner: kiransingh
--

INSERT INTO public.fair VALUES (1, 1, 'Hasselt 2021', 'Lorem Ipsum is slechts een proeftekst uit het drukkerij- en zetterijwezen. Lorem Ipsum is de standaard proeftekst in deze bedrijfstak sinds de 16e eeuw, toen een onbekende drukker een zethaak met letters nam en ze door elkaar husselde om een font-catalogus te maken. Het heeft niet alleen vijf eeuwen overleefd maar is ook, vrijwel onveranderd, overgenomen in elektronische letterzetting. Het is in de jaren ''60 populair geworden met de introductie van Letraset vellen met Lorem Ipsum passages en meer recentelijk door desktop publishing software zoals Aldus PageMaker die versies van Lorem Ipsum bevatten.', '2021-01-06', '2021-01-10', '17:00:00', '23:00:00', 'hasselt', 2);
INSERT INTO public.fair VALUES (9, 3, 'Tongeren 2021', 'Tongeren 2021Tongeren 2021Tongeren 2021Tongeren 2021Tongeren 2021Tongeren 2021Tongeren 2021Tongeren 2021Tongeren 2021Tongeren 2021Tongeren 2021Tongeren 2021Tongeren 2021Tongeren 2021Tongeren 2021Tongeren 2021Tongeren 2021Tongeren 2021Tongeren 2021Tongeren 2021Tongeren 2021Tongeren 2021', '2021-01-10', '2021-01-14', '17:00:00', '23:59:00', 'Tongeren', 2);


--
-- Data for Name: messaging; Type: TABLE DATA; Schema: public; Owner: kiransingh
--

INSERT INTO public.messaging VALUES (1, 12, 9, 'hello', '2021-01-06 18:14:05.013675', true, true);
INSERT INTO public.messaging VALUES (5, 12, 9, 'qsd', '2021-01-07 00:24:46.837038', true, true);
INSERT INTO public.messaging VALUES (6, 12, 9, 'f', '2021-01-07 00:24:49.173151', true, true);
INSERT INTO public.messaging VALUES (8, 12, 9, 'dxgf', '2021-01-09 14:28:49.489861', true, true);
INSERT INTO public.messaging VALUES (9, 9, 12, 'hello', '2021-01-09 14:48:41.118192', false, false);
INSERT INTO public.messaging VALUES (10, 9, 12, 'v1', '2021-01-09 14:48:47.07138', false, false);
INSERT INTO public.messaging VALUES (11, 9, 12, 'v1', '2021-01-09 14:51:00.380417', false, false);
INSERT INTO public.messaging VALUES (4, 9, 12, 'sdf', '2021-01-07 00:24:16.210421', true, true);
INSERT INTO public.messaging VALUES (2, 9, 12, 'hello', '2021-01-06 18:14:11.117998', true, true);
INSERT INTO public.messaging VALUES (3, 9, 12, 'fdsg', '2021-01-06 18:14:20.889632', true, true);
INSERT INTO public.messaging VALUES (7, 9, 12, 'dsgdfgdf', '2021-01-09 14:28:42.561415', true, true);
INSERT INTO public.messaging VALUES (12, 9, 12, 'v1', '2021-01-09 14:51:07.192584', false, false);
INSERT INTO public.messaging VALUES (13, 9, 12, 'v1', '2021-01-09 14:51:52.191585', false, false);


--
-- Data for Name: notifications; Type: TABLE DATA; Schema: public; Owner: kiransingh
--



--
-- Data for Name: password_reset; Type: TABLE DATA; Schema: public; Owner: kiransingh
--

INSERT INTO public.password_reset VALUES (2, 'cebeyoc189@28woman.com', 'a009d651b056ab2977488df6b6dae829');


--
-- Data for Name: zones; Type: TABLE DATA; Schema: public; Owner: kiransingh
--

INSERT INTO public.zones VALUES (1, 1, 'Zone1', 'Lorem Ipsum is slechts een proeftekst uit het drukkerij- en zetterijwezen. Lorem Ipsum is de standaard proeftekst in deze bedrijfstak sinds de 16e eeuw, toen een onbekende drukker een zethaak met letters nam en ze door elkaar husselde om een font-catalogus te maken. Het heeft niet alleen vijf eeuwen overleefd maar is ook, vrijwel onveranderd, overgenomen in elektronische letterzetting. Het is in de jaren ''60 populair geworden met de introductie van Letraset vellen met Lorem Ipsum passages en meer recentelijk door desktop publishing software zoals Aldus PageMaker die versies van Lorem Ipsum bevatten.', 'hasselt', 100, 'attraction_NAME_1,attraction_NAME_2,attraction_NAME_3', 2, 1);
INSERT INTO public.zones VALUES (12, 9, 'Zone1', 'Zone1Zone1Zone1Zone1Zone1Zone1Zone1Zone1Zone1Zone1 Zone1Zone1Zone1Zone1Zone1Zone1Zone1Zone1Zone1', 'Tongeren', 10, 'ATTRACTION-NAME-1,ATTRACTION-NAME-2,ATTRACTION-NAME-3', 2, 1);


--
-- Data for Name: zoneslots; Type: TABLE DATA; Schema: public; Owner: kiransingh
--

INSERT INTO public.zoneslots VALUES (1, 1, '17:01:00', '18:00:00', 100, '2021-01-06');
INSERT INTO public.zoneslots VALUES (3, 1, '17:01:00', '18:00:00', 100, '2021-01-08');
INSERT INTO public.zoneslots VALUES (5, 1, '17:01:00', '18:00:00', 100, '2021-01-10');
INSERT INTO public.zoneslots VALUES (6, 1, '19:00:00', '20:00:00', 100, '2021-01-06');
INSERT INTO public.zoneslots VALUES (7, 1, '19:00:00', '20:00:00', 100, '2021-01-07');
INSERT INTO public.zoneslots VALUES (8, 1, '19:00:00', '20:00:00', 100, '2021-01-08');
INSERT INTO public.zoneslots VALUES (9, 1, '19:00:00', '20:00:00', 100, '2021-01-09');
INSERT INTO public.zoneslots VALUES (103, 12, '17:01:00', '18:30:00', 10, '2021-01-10');
INSERT INTO public.zoneslots VALUES (104, 12, '17:01:00', '18:30:00', 10, '2021-01-11');
INSERT INTO public.zoneslots VALUES (105, 12, '17:01:00', '18:30:00', 10, '2021-01-12');
INSERT INTO public.zoneslots VALUES (106, 12, '17:01:00', '18:30:00', 10, '2021-01-13');
INSERT INTO public.zoneslots VALUES (107, 12, '17:01:00', '18:30:00', 10, '2021-01-14');
INSERT INTO public.zoneslots VALUES (108, 12, '18:30:00', '19:30:00', 10, '2021-01-10');
INSERT INTO public.zoneslots VALUES (109, 12, '18:30:00', '19:30:00', 10, '2021-01-11');
INSERT INTO public.zoneslots VALUES (110, 12, '18:30:00', '19:30:00', 10, '2021-01-12');
INSERT INTO public.zoneslots VALUES (111, 12, '18:30:00', '19:30:00', 10, '2021-01-13');
INSERT INTO public.zoneslots VALUES (112, 12, '18:30:00', '19:30:00', 10, '2021-01-14');
INSERT INTO public.zoneslots VALUES (4, 1, '17:01:00', '18:00:00', 98, '2021-01-09');
INSERT INTO public.zoneslots VALUES (10, 1, '19:00:00', '20:00:00', 100, '2021-01-10');
INSERT INTO public.zoneslots VALUES (2, 1, '17:01:00', '18:00:00', 100, '2021-01-07');


--
-- Data for Name: reservations; Type: TABLE DATA; Schema: public; Owner: kiransingh
--

INSERT INTO public.reservations VALUES (5, 9, 4, 1, 2, true, NULL, NULL);


--
-- Data for Name: review; Type: TABLE DATA; Schema: public; Owner: kiransingh
--

INSERT INTO public.review VALUES (1, 1, 9, 3, 'hellohellohellohello');
INSERT INTO public.review VALUES (2, 1, 9, 5, 'Hasselt 2021

Hasselt 2021
Hasselt 2021Hasselt 2021Hasselt 2021Hasselt 2021Hasselt 2021Hasselt 2021Hasselt 2021Hasselt 2021
Hasselt 2021Hasselt 2021Hasselt 2021Hasselt 2021Hasselt 2021Hasselt 2021');


--
-- Data for Name: waitinglist; Type: TABLE DATA; Schema: public; Owner: kiransingh
--



--
-- Name: accounts_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kiransingh
--

SELECT pg_catalog.setval('public.accounts_user_id_seq', 14, true);


--
-- Name: city_city_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kiransingh
--

SELECT pg_catalog.setval('public.city_city_id_seq', 5, true);


--
-- Name: fair_fair_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kiransingh
--

SELECT pg_catalog.setval('public.fair_fair_id_seq', 9, true);


--
-- Name: messaging_message_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kiransingh
--

SELECT pg_catalog.setval('public.messaging_message_id_seq', 13, true);


--
-- Name: notifications_notification_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kiransingh
--

SELECT pg_catalog.setval('public.notifications_notification_id_seq', 2, true);


--
-- Name: password_reset_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kiransingh
--

SELECT pg_catalog.setval('public.password_reset_id_seq', 2, true);


--
-- Name: reservations_reservation_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kiransingh
--

SELECT pg_catalog.setval('public.reservations_reservation_id_seq', 5, true);


--
-- Name: review_review_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kiransingh
--

SELECT pg_catalog.setval('public.review_review_id_seq', 2, true);


--
-- Name: waitinglist_placement_seq; Type: SEQUENCE SET; Schema: public; Owner: kiransingh
--

SELECT pg_catalog.setval('public.waitinglist_placement_seq', 1, true);


--
-- Name: zones_zone_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kiransingh
--

SELECT pg_catalog.setval('public.zones_zone_id_seq', 12, true);


--
-- Name: zoneslots_zoneslot_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kiransingh
--

SELECT pg_catalog.setval('public.zoneslots_zoneslot_id_seq', 112, true);


--
-- PostgreSQL database dump complete
--

