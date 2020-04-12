-- LGAMITO
--
-- Estrutura da tabela `config_preventiva`
--

CREATE TABLE IF NOT EXISTS `config_preventiva` (
  `id` int(4) NOT NULL auto_increment,
  `conf_num_chamado` int(4) NOT NULL,
  `conf_tempo_min` int(4) NOT NULL,
  `conf_tempo_max` int(4) NOT NULL,
  `conf_maq_nova` int(4) NOT NULL,
  `conf_data_inic` date NOT NULL,
  `conf_tipo_equip` varchar(20) default NULL,
  `conf_equip_situac` varchar(20) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Configuração de Preventivas' AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `config_preventiva`
--

INSERT INTO `config_preventiva` (`id`, `conf_num_chamado`, `conf_tempo_min`, `conf_tempo_max`, `conf_maq_nova`, `conf_data_inic`, `conf_tipo_equip`, `conf_equip_situac`) VALUES
(1, 50, 90, 120, 120, '2012-01-01', '1,2,3', '1,3,8,10,11');

-- 
-- Extraindo dados da tabela `sistemas`
-- 

/*
Script de Alteração de Tabela

Essa é a inclusão dos Campos a mais da versão lrgamito
*/

ALTER TABLE `equipamentos` 
 ADD(
 `comp_leitor` int(5) unsigned default NULL,
 `comp_os` int(5) unsigned default NULL,
 `comp_sn_os` varchar(50) default NULL
 );
 
/*
Descomente as linhas de baixo se quizer um código de etiqueta que usa ALFANUMÉRICOS
*/
/*
 ALTER TABLE `equipamentos` 
 CHANGE comp_inv comp_inv varchar(20);
 */