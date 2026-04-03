CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    admin VARCHAR(1) NOT NULL UNIQUE
);

CREATE TABLE `fotos` (
  `id_fotos` int(11) NOT NULL AUTO_INCREMENT,
  `nome_arquivo` varchar(50) NOT NULL,
  `data` date NOT NULL,
  `id_setor` int(11) DEFAULT NULL,
  `id_subsecao` int(11) DEFAULT NULL,
  `id_local` int(11) DEFAULT NULL,
  `id_ocorrencia` int(11) DEFAULT NULL,
  `observacao` varchar(200) NOT NULL,
  `conforme` varchar(1) NOT NULL,
  `lcastanheira` int(11) DEFAULT NULL,
  `limbauba` int(11) DEFAULT NULL,
  `lpaubrasil` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_fotos`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `lista_castanheira` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item` varchar(10) NOT NULL,
  `desc_item` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `lista_imbauba` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item` varchar(10) NOT NULL,
  `desc_item` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `lista_pau_brasil` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item` varchar(10) NOT NULL,
  `desc_item` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `local` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `local` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `ocorrencia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ocorrencia` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `setores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setor` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `subsecoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subsecao` varchar(30) NOT NULL,
  `setor_superior` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `usuarios` (`id`, `nome`, `usuario`, `senha`, `email`, `admin`) VALUES
(1, 'douglas', 'douglas', '$2y$10$JcW/MUHytTPl6/.XxVWTYuwYk7c7Qf9bawSa95twUkl7vqo3E9HXy', 'douglas.marcondes@gmail.com', 'S');

INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('1', '01', 'Plano de Gestão Ambiental', 'Possui o Plano de Gestão Ambiental atualizado conforme IR 50-20.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('2', '02', 'Capacitação para Impactos Ambientais', 'Envolvidos em atividades de impacto ambiental negativo possuem capacitação específica.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('3', '03', 'Conhecimento dos Impactos Negativos', 'A OM tem conhecimento dos impactos ambientais negativos não tratados.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('4', '04', 'Solicitação de Apoio para Impactos Negativos', 'A OM solicitou apoio e recursos para tratar os impactos ambientais negativos identificados.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('5', '05', 'Interações com o Meio Ambiente', 'A OM conhece suas atividades mais importantes que interagem com o meio ambiente.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('6', '06', 'Ações para Minimizar Impactos Negativos', 'Existem ações para minimizar os impactos ambientais negativos da OM.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('7', '07', 'Gestão à Vista', 'A OM usa gestão à vista para comunicação e divulgação de capacitação ambiental e indicadores.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('8', '08', 'Educação Ambiental Específica', 'A OM desenvolve trabalhos de educação ambiental para os integrantes, como palestras e folhetos.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('9', '09', 'Segregação de Resíduos', 'Existe segregação mínima de resíduos comuns com coletores específicos e em quantidade adequada.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('10', '10', 'Destinação de Resíduos Recicláveis', 'A OM destina resíduos recicláveis a cooperativas e empresas de reciclagem licenciadas.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('11', '11', 'Acondicionamento de Resíduos Orgânicos', 'A OM acondiciona resíduos orgânicos em coletores tampados e protegidos.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('12', '12', 'Política de Redução de Desperdício Orgânico', 'A OM possui política para redução de desperdício de resíduos orgânicos.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('13', '13', 'Controle de Quantidade de Resíduos', 'A OM controla a quantidade diária, semanal e mensal de resíduos produzidos.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('14', '14', 'Controle dos Tipos de Resíduos', 'A OM controla os tipos de resíduos gerados conforme as atividades.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('15', '15', 'Destinação de Resíduos de Animais', 'Os resíduos de dejetos dos animais são tratados para evitar destinação pública.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('16', '16', 'Destinação por Logística Reversa', 'Resíduos sólidos são destinados prioritariamente por logística reversa, conforme PNRS.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('17', '17', 'Limpeza de Caixas de Gordura e Fossas', 'A OM realiza limpeza periódica das caixas de gordura e fossas sépticas.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('18', '18', 'Comissão de Gerenciamento de Resíduos de Saúde', 'Há comissão de gerenciamento de resíduos de saúde, publicada em BI.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('19', '19', 'Destinação de Óleos Lubrificantes', 'Óleos usados para rerrefino são destinados a empresas licenciadas pela ANP.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('20', '20', 'Controle de Consumo de Água', 'A OM possui registro de controle do consumo de água.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('21', '21', 'Conservação das Caixas de Gordura', 'As caixas de gordura estão bem conservadas e funcionais.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('22', '22', 'Manutenção de Sistemas de Efluentes', 'A OM realiza manutenção adequada dos sistemas de tratamento de efluentes.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('23', '23', 'Operador Qualificado para ETE/ETA', 'A OM possui empresa ou operador capacitado para ETE/ETA.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('24', '24', 'Autorização para Poço Artesiano Desativado', 'A OM tem autorização para desativação de poço do órgão competente.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('25', '25', 'Análise dos Pontos de Consumo Externo', 'A OM analisa os pontos de consumo para atendimento ao público externo.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('26', '26', 'Separação de Água da Chuva do Esgoto', 'A OM possui estrutura para separar a água da chuva do esgoto.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('27', '27', 'Critérios de Sustentabilidade em Licitações', 'Existem critérios de sustentabilidade nos processos de licitação.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('28', '28', 'Logística Reversa em Contratos', 'A OM inclui logística reversa de produtos em contratos e licitações.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('29', '29', 'Identificação de Áreas Degradadas', 'As áreas degradadas da OM foram identificadas.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('30', '30', 'Monitoramento de Recuperação de Áreas', 'A OM monitora a recuperação das áreas degradadas.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('31', '31', 'Responsabilidade Ambiental em Contratos de Arrendamento', 'Contratos de áreas arrendadas definem responsabilidade ambiental.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('32', '32', 'Cadastro e Autorização do P Distr Cl III', 'O Posto de Distribuição Classe III está cadastrado e autorizado pela ANP.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('33', '33', 'Limpeza da Caixa Separadora de Água e Óleo', 'A OM realiza limpeza periódica da caixa separadora de água e óleo.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('34', '34', 'Identificação de Produtos Químicos', 'Produtos químicos fracionados são identificados e rotulados com simbologia de risco.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('35', '35', 'Estocagem de Produtos Inflamáveis', 'Produtos inflamáveis são estocados em ambientes resistentes ao fogo.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('36', '36', 'Posição dos Produtos Corrosivos no Estoque', 'Produtos corrosivos são armazenados na parte inferior do estoque.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('37', '37', 'Extintores e Avisos de Não Fumar', 'Existem extintores e avisos de \"Não Fumar\" próximos à estocagem de inflamáveis.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('38', '38', 'Plano de Manutenção de Máquinas', 'A OM possui plano de manutenção de máquinas e equipamentos.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('39', '39', 'Lacre e Segurança dos Extintores', 'Extintores estão com lacre e grampo de segurança.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('40', '40', 'Certificação da Empresa de Extintores', 'A empresa de extintores é certificada pelo Corpo de Bombeiros e INMETRO.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('41', '41', 'Capacitação para Uso de Extintores', 'Há militares e/ou civis capacitados para manusear extintores e hidrantes.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('42', '42', 'Sinalização de Emergência', 'A OM possui sinalização de emergência, incluindo rotas de fuga.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('43', '43', 'Sistema de Combate a Incêndio', 'Sistema de combate a incêndio está em boas condições e passa por manutenções.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('44', '44', 'Sirene de Alerta', 'A OM possui sirene de alerta.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('45', '45', 'Plano de Prevenção e Combate a Incêndio', 'A OM possui plano de prevenção e combate a incêndio do ano corrente.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('46', '46', 'Aceiros Florestais', 'A unidade realiza aceiros florestais.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('47', '47', 'Programa para Reduzir Perdas e Desperdícios de Água', 'A OM possui programa para reduzir e prevenir desperdícios de água.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('48', '48', 'Economia de Energia', 'A OM possui métodos para proporcionar economia de energia elétrica.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('49', '49', 'Ventilação e Iluminação Natural', 'A OM prioriza o uso de ventilação e iluminação natural.');
INSERT INTO lista_castanheira (id, item, desc_item, descricao) VALUES ('50', '50', 'Controle Integrado de Pragas e Vetores', 'A OM possui programa integrado de controle de pragas e vetores.');

INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('1', '01', 'Materiais de Amianto', 'A OM ainda possui telhas, caixas d´água ou qualquer material de amianto?');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('2', '02', 'Publicação do Responsável Ambiental', 'O responsável pelo meio ambiente foi publicado em boletim junto com seu substituto?');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('3', '03', 'Capacitação em Gestão Ambiental', 'Os responsáveis pela gestão ambiental possuem capacitação pelo AVPIMA?');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('4', '04', 'Plano de Gerenciamento de Resíduos Sólidos', 'Possui PGRS com responsável técnico conforme legislação vigente?');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('5', '05', 'Local para Armazenamento de Resíduos', 'Possui local apropriado para armazenamento de resíduos diversos?');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('6', '06', 'Armazenamento de Pneus Usados', 'A OM armazena pneus usados em local protegido contra intempéries?');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('7', '07', 'Acondicionamento de Resíduos Perigosos', 'Resíduos perigosos são acondicionados conforme legislação?');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('8', '08', 'Armazenamento de Óleos e Graxas', 'Óleos e graxas são armazenados em recipientes resistentes a vazamentos?');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('9', '09', 'Transporte de Resíduos Perigosos', 'Resíduos perigosos são transportados conforme legislação?');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('10', '10', 'Destinação de Resíduos Perigosos', 'Resíduos perigosos são destinados para empresas licenciadas conforme legislação?');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('11', '11', 'Destinação de Resíduos Não Perigosos', 'Resíduos não perigosos são destinados conforme legislação?');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('12', '12', 'Plano de Gerenciamento de Resíduos de Saúde', 'Possui PGRSS com responsável técnico conforme legislação vigente?');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('13', '13', 'Armazenamento de Resíduos de Saúde', 'Resíduos de saúde são armazenados adequadamente conforme legislação?');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('14', '14', 'Posição de Recipientes Perfurocortantes', 'Recipientes perfurocortantes estão posicionados adequadamente?');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('15', '15', 'Transporte Interno de Resíduos de Saúde', 'Transporte interno de resíduos de saúde é realizado conforme legislação vigente?');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('16', '16', 'Transporte Externo de Resíduos de Saúde', 'Transporte externo de resíduos de saúde é feito com segurança e em bombonas lacradas.');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('17', '17', 'Destinação de Resíduos de Saúde', 'Destinação final dos resíduos de saúde é realizada para OM ou empresa licenciada.');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('18', '18', 'Comprovante de Transporte de Resíduos', 'Todos os resíduos possuem CTR ou MTR conforme legislação?');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('19', '19', 'Certificado de Destinação Final de Resíduos', 'Todos os resíduos possuem Certificado de Destinação Final conforme legislação vigente.');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('20', '20', 'Cadastro de Usuários de Recursos Hídricos', 'OM que usa recursos hídricos possui o CNARH conforme exigido?');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('21', '21', 'Outorga para Captação de Água', 'Captação de água por poços ou corpos de água é outorgada ou dispensada conforme legislação?');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('22', '22', 'Controle de Qualidade da Água', 'OM sem abastecimento de concessionária controla qualidade da água conforme legislação?');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('23', '23', 'Higienização de Reservatórios de Água', 'Bebedouros e reservatórios são higienizados periodicamente conforme legislação?');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('24', '24', 'Licença para Operação de ETE', 'OM possui inexigibilidade de licença ou licença para Operação de ETE?');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('25', '25', 'Monitoramento de Efluentes Tratados', 'OM monitora tratamento e lançamento de efluentes conforme legislação?');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('26', '26', 'Outorga para Lançamento de Efluentes', 'OM que possui ETE tem outorga para lançamento do efluente tratado em corpos de água?');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('27', '27', 'Tratamento de Efluentes', 'Todos os efluentes gerados são tratados conforme a legislação vigente.');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('28', '28', 'Respeito às Áreas de Preservação Permanente', 'Áreas de Preservação Permanente são respeitadas conforme a legislação vigente?');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('29', '29', 'Autorização de Supressão Vegetal', 'Supressão vegetal é realizada com Autorização de Supressão Vegetal (ASV).');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('30', '30', 'Licença para Uso de Motosserra', 'Motosserra e operador da OM possuem licença para porte e uso no IBAMA.');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('31', '31', 'Projeto de Recuperação de Áreas Degradadas', 'Áreas degradadas possuem PRAD em andamento com cronograma.');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('32', '32', 'Cadastro e Autorização do P Distr Cl III', 'O Posto de Distribuição Classe III está cadastrado e autorizado pela ANP.');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('33', '33', 'Atendimento das Condicionantes do P Distr Cl III', 'P Distr Cl III licenciado possui suas condicionantes atendidas.');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('34', '34', 'Área Impermeável em Postos de Abastecimento', 'Postos possuem área impermeável com canaletas para caixa separadora de água e óleo.');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('35', '35', 'Separação de Águas Contaminadas', 'Águas de chuva e de lavagem de viaturas com óleo são direcionadas para caixa separadora.');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('36', '36', 'Bacias de Contenção para Tanques de Combustíveis', 'Tanques possuem bacias de contenção dimensionadas conforme legislação.');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('37', '37', 'Armazenamento de Produtos Químicos', 'Produtos químicos armazenados com bacias de contenção adequadas.');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('38', '38', 'Manuseio de Produtos Químicos', 'Produtos químicos são manuseados conforme FDS disponível próxima ao produto.');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('39', '39', 'Controle de Emissões Atmosféricas', 'Fumaça de escapamentos de veículos a diesel está de acordo com a escala Ringelmann.');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('40', '40', 'Plano de Manutenção de Climatização (PMOC)', 'OM possui PMOC dos sistemas de climatização e segue o plano.');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('41', '41', 'Equipe de Combate a Incêndio', 'Existe equipe treinada para combate a incêndio publicada em BI.');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('42', '42', 'Equipamentos de Combate a Incêndio', 'Equipamentos estão visíveis, sinalizados e dimensionados conforme previsto.');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('43', '43', 'Plano de Atendimento à Emergências', 'OM possui Plano de Atendimento à Emergências ambientais para transporte de resíduos.');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('44', '44', 'Kit de Emergência para Derramamento', 'OM dispõe de kit de emergência para derramamento de óleo/produtos químicos.');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('45', '45', 'Licença para Dedetização e Desratização', 'Empresa responsável por dedetização e desratização possui licença e registro.');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('46', '46', 'Destinação Adequada de Resíduos em Campos de Instrução', 'OM destina adequadamente resíduos nos campos de instrução.');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('47', '47', 'Preservação das Árvores em Campos de Instrução', 'Preservação da área é realizada evitando o corte de árvores.');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('48', '48', 'Preservação dos Cursos de água', 'Preservação de cursos de água é realizada nos campos de instrução.');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('49', '49', 'Preservação dos Animais Silvestres', 'Preservação dos animais silvestres é realizada durante instruções.');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('50', '50', 'Equipe de Prevenção de Incêndios em Acampamentos', 'Equipe de prevenção e combate a incêndios é designada em acampamentos.');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('51', '51', 'Prevenção de Acidentes com Animais Peçonhentos', 'OM realiza instruções para prevenir acidentes com animais peçonhentos.');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('52', '52', 'Proteção contra Vetores de Doenças', 'OM toma medidas de proteção contra vetores durante manobras e acampamentos.');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('53', '53', 'Cuidados Ambientais em Ordens de Instrução', 'Ordens de instrução incluem cuidados ambientais.');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('54', '54', 'Recuperação da Cobertura Vegetal no Estande de Tiro', 'Prevê-se recuperação da cobertura vegetal nas áreas de erosão.');
INSERT INTO lista_imbauba (id, item, desc_item, descricao) VALUES ('55', '55', 'Uso de Cal nas Áreas de Latrina', 'Cal e material seco são colocados nas áreas de latrina durante instruções.');

INSERT INTO lista_pau_brasil (id, item, desc_item, descricao) VALUES ('1', '01', 'Objetivos e Metas', 'Os objetivos e metas foram definidos de forma desafiadora e alcançável no PGA.');
INSERT INTO lista_pau_brasil (id, item, desc_item, descricao) VALUES ('2', '02', 'Controle de Recursos Naturais', 'A OM controla o consumo de todos os recursos naturais, como água e energia, por indicadores.');
INSERT INTO lista_pau_brasil (id, item, desc_item, descricao) VALUES ('3', '03', 'Consumo de Papel', 'A OM acompanha o consumo de papel usado para impressão e cópias.');
INSERT INTO lista_pau_brasil (id, item, desc_item, descricao) VALUES ('4', '04', 'Uso de Papel Reciclado', 'A OM utiliza papel não-clorado ou reciclado.');
INSERT INTO lista_pau_brasil (id, item, desc_item, descricao) VALUES ('5', '05', 'Reutilização de Papel', 'A OM promove a reutilização do papel A4 antes de enviá-lo para reciclagem, como na confecção de blocos de anotação.');
INSERT INTO lista_pau_brasil (id, item, desc_item, descricao) VALUES ('6', '06', 'Campanhas de Conscientização', 'A OM promove campanhas de racionalização para uso consciente de copos plásticos, energia, água e papel.');
INSERT INTO lista_pau_brasil (id, item, desc_item, descricao) VALUES ('7', '07', 'Disponibilização de Copos Permanentes', 'A OM disponibiliza copos permanentes para todos os militares e servidores.');
INSERT INTO lista_pau_brasil (id, item, desc_item, descricao) VALUES ('8', '08', 'Redução do Consumo de Energia', 'A OM utiliza equipamentos que reduzem o consumo de energia, como ar condicionado eficiente e sensores de presença.');
INSERT INTO lista_pau_brasil (id, item, desc_item, descricao) VALUES ('9', '09', 'Campanhas sobre Uso de Fumo e Álcool', 'A OM promove campanhas sobre o uso de fumo e álcool.');
INSERT INTO lista_pau_brasil (id, item, desc_item, descricao) VALUES ('10', '10', 'Incentivo ao Uso de Bicicletas', 'A OM possui bicicletário para incentivar o uso de bicicletas pelos militares e servidores.');
INSERT INTO lista_pau_brasil (id, item, desc_item, descricao) VALUES ('11', '11', 'Distribuição de Kits Ambientais', 'A OM distribui kits ambientais com instruções sobre qualidade de vida.');
INSERT INTO lista_pau_brasil (id, item, desc_item, descricao) VALUES ('12', '12', 'Controle da Qualidade do Ar', 'A OM monitora a qualidade do ar em ambientes coletivos com ar condicionado.');
INSERT INTO lista_pau_brasil (id, item, desc_item, descricao) VALUES ('13', '13', 'Ginástica Laboral', 'A OM oferece ginástica laboral e equipamentos ergométricos para os militares e servidores.');
INSERT INTO lista_pau_brasil (id, item, desc_item, descricao) VALUES ('14', '14', 'Consumo de Energia Solar', 'A OM utiliza placas solares para consumo de energia.');
INSERT INTO lista_pau_brasil (id, item, desc_item, descricao) VALUES ('15', '15', 'Uso de Madeira Certificada', 'A OM prioriza o uso de madeira certificada e materiais de fontes sustentáveis.');
INSERT INTO lista_pau_brasil (id, item, desc_item, descricao) VALUES ('16', '16', 'Parcerias de Educação Ambiental', 'A OM participa de parcerias no desenvolvimento de programas de educação e conservação ambiental.');
INSERT INTO lista_pau_brasil (id, item, desc_item, descricao) VALUES ('17', '17', 'Cartilhas Educativas', 'A OM desenvolve cartilhas educativas sobre sustentabilidade para capacitação de militares e servidores.');
INSERT INTO lista_pau_brasil (id, item, desc_item, descricao) VALUES ('18', '18', 'Celebração de Datas Comemorativas', 'A OM celebra datas comemorativas relacionadas à sustentabilidade.');
INSERT INTO lista_pau_brasil (id, item, desc_item, descricao) VALUES ('19', '19', 'Cláusulas de Educação Ambiental em Contratos', 'A OM inclui cláusulas de capacitação em educação ambiental para funcionários terceirizados.');
INSERT INTO lista_pau_brasil (id, item, desc_item, descricao) VALUES ('20', '20', 'Coletores para Resíduos Recicláveis', 'A OM possui coletores para tipos de resíduos recicláveis e orgânicos em quantidade suficiente.');
INSERT INTO lista_pau_brasil (id, item, desc_item, descricao) VALUES ('21', '21', 'Reutilização de Resíduos Orgânicos', 'A OM reutiliza resíduos orgânicos para biodigestão e/ou compostagem.');
INSERT INTO lista_pau_brasil (id, item, desc_item, descricao) VALUES ('22', '22', 'Coletores de Lixo Orgânico e Seco', 'A OM possui coletores para separação de lixo orgânico e seco nas copas.');
INSERT INTO lista_pau_brasil (id, item, desc_item, descricao) VALUES ('23', '23', 'Estudo de Viabilidade para Ecoponto', 'A OM estuda a viabilidade de um ecoponto para coleta de pilhas, baterias e óleo de cozinha.');
INSERT INTO lista_pau_brasil (id, item, desc_item, descricao) VALUES ('24', '24', 'Coleta de Resíduos Perigosos', 'A OM possui contratos com cooperativas para coleta e destinação de resíduos perigosos.');
INSERT INTO lista_pau_brasil (id, item, desc_item, descricao) VALUES ('25', '25', 'Levantamento de Resíduos Orgânicos', 'A OM realiza levantamento de resíduos orgânicos em restaurantes e lanchonetes para destinação adequada.');
INSERT INTO lista_pau_brasil (id, item, desc_item, descricao) VALUES ('26', '26', 'Destinação de Resíduos de Construção Civil', 'A OM vincula ao contrato de obra a destinação correta dos resíduos de construção civil.');
INSERT INTO lista_pau_brasil (id, item, desc_item, descricao) VALUES ('27', '27', 'Plantio de Árvores', 'A OM realiza o plantio de mudas e árvores.');
INSERT INTO lista_pau_brasil (id, item, desc_item, descricao) VALUES ('28', '28', 'Reuso de Água para Lavagem de Veículos', 'A OM utiliza água de captação de chuvas para lavagem de viaturas.');