<?php
// arquivo-de-lingua para area de administra��o do phorum-3.4.x

##############################################################
# Traduzido por: Gabriel Butzke                              #
# E-mail: gb@unerj.br                                        #
# Data: 13/03/2003                                           #
# Ajuda: Coloque este arquivo na pasta admin/lang do Phorum  #
#        e quando for instal�-lo, no passo 1 em              #
#        Select Installation-Language: selecione portugu�s.  #
##############################################################

// IN�CIO - vari�veis de instala��o do f�rum:
$lTitle            = "Instala��o do Phorum";
$lAdmin_Perm_Check = "-<b>Checando permiss�es:</b><br />";
$lAdmin_Perm_Ok    = " est� <font class='check_ok'>[OK]</font><br \>";
$lAdmin_Perm_Bad   = " est� <font class='check_bad'>[Ruim]</font><br \><font class=error>Arrume: mude permiss�es para 777 ou 666</font><br />";
$lHelp             = "ajuda";
$lNextStep	   = "-Pr�ximo Passo-";
$lFinish           = "-Passo Final-";
$lStep1_end        = "Passo 1: Completo.<br \>";
$lStep2		   = "Passo 2: Tipo do Banco de Dados.";
$lDBType	   = "Entre o Tipo do Banco de Dados:";
$lStep2_end	   = "Passo 2: Completo.<br \>";
$lStep3		   = "Passo 3: Configura��es do Banco de Dados.";
$lStep3_end	   = "<br \>Passo 3: Completo.<br \>";
$lStep4            = "Passo 4: Administrador.";
$lStep4_end	   = "Passo 4: Completo.<br \>";
$lStep5            = "Passo 5: �ltimo Passo.";
$lDBServer	   = "Banco de Dados - Nome do Servidor:";
$lDBName	   = "Banco de Dados - Nome:";
$lDBUser	   = "Banco de Dados - Nome do Usu�rio:";
$lDBPass	   = "Banco de Dados - Senha:";
$lPhorumTable      = "Phorum - Nome da Tabela Principal:";
$lUpdate           = "Selecione se isto � uma atualiza��o.<br />Leia docs/upgrade.txt para maiores informa��es sobre suas configura��es.";
$lAttachmentDir    = "Se voc� preferir atualizar atachamentos tamb�m, apenas entre com o caminho do diret�rio aqui como estava na sua antiga instala��o.";
$lDBNote	   = "Obs.:  Se o SQL Safe Mode est� ativado no seu servidor, deixe o usu�rio e a senha em branco.";
$lAdminUser        = "Nome do Usu�rio:";
$lAdminPass        = "Senha:";
$lAdminPass2       = "(novamente)";
$lPhorum_URL       = "Phorum URL:";
$lAdminEmail       = "Email do Administrador:";
$lDefaultEmail     = "Email do Phorum:";
$lAdminName        = "Mostre o nome:";
$lErrorFile        = "N�o foi poss�vel abrir ou encontrar o banco de dados. Verifique se voc� informou o diret�rio do banco de dados.";
$lErrorDB	   = "N�o pode conectar o banco de dados.  Verifique suas configura��es novamente.";
$lErrorTables      = "O Mysql n�o pode criar as tabelas. Talvez seja para atualizar?";
$lErrorFields      = "Por favor preencha todos os campos";
$lErrorPass        = "Senha Inv�lida";
$lErrorWrongPass   = "Usu�rio existente, mas senha est� incorreta.";
$lErrorDBAdmin     = "N�o pode criar usu�rio administrador. Banco de dados retornou: ";
$lErrorURL         = "N�o � uma URL v�lida";
$lErrorEmail       = "N�o � um endere�o de email v�lido";
$lErrorName        = "Nome est� em branco";
$lDB_Ok            = "Configura��es do Banco de Dados est�o corretas!<br />\n";
$lDB_Upgrade       = "Atualizando tabelas...<br />";
$lDB_Create        = "Criando tabelas iniciais...<br />\n";
$lDB_Create_done   = "<b>Tabelas Criadas!</b><br />\n";
$lUserExists       = "Usu�rio est� no banco de dados...<br />\n";
$lUserIsAdmin      = "$AdminUser j� � um administrador.<br />\n";
$lUserAdmin        = "$AdminUser n�o era um administrador, mas agora � :)<br />\n";
$lAdminCreated     = "Usu�rio Administrador Criado<br />\n";
$lFINAL            = "Parab�ns!  <a href=\"$PHP_SELF\">Clique aqui</a> para ir para a parte de administra��o do Phorum.";
// FINAL - vari�veis de instala��o do f�rum;
// IN�CIO - texto de ajuda da instala��o
$lHelpTitle        = "Ajuda para Instala��o do Phorum";
$lCloseWindow      = "Fechar.";
$lStep1_help       = "Seja Bem-Vindo ao Script de Instala��o do Phourm. Eu tentarei ajudar os iniciantes ao Phorum atrav�s da instala��o.  Tudo que voc� precisa fazer � ir at� o passo 1 escolher a l�ngua da sua instala��o.";
$lStep2_help       = "No topo da tela de cada instala��o voc� ver� a informa��o sobre a a��o que acontecer� ap�s voc� clicar no bot�o.  No �ltimo passo, phorum verfica se as permissoes para os arquivos que o phorum utilizar� est�o corretas.  Se voc� ver <font class='check_bad'>[Bad]</font> pr�ximo ao forums.php ou diret�rio de configura��es, voc� ter� que utilizar o CHMOD ou um programa de FTP para alterar a permiss�o 777 ou 666 (qualquer uma funcionar�, mas permiss�es 666 provavelmente n�o funcionar�o em alguns servidores). Isto � geralmente feito com um programa de cliente de FTP como por exemplo SmartFTP clique em file/dir e escolhendo \"Fie Permissions\" ou \"Chmod\" command.  Voc� n�o precisa apertar o bot�o de \"Atualizar\" do seu navegador se voc� tem certeza que selecionou as permissoes corretas, mas se voc� n�o tem certeza, o Phorum checar� novamente as permiss�es depois que voc� atualizar o seu navegador.<br \><br \> Depois que voc� organizar as permiss�es, � hora de escolher o banco de dados que seu servidor possui.  O Phorum suporta MySQL e PostgreSQL.  MySQL � o mais utilizado, mas eu sugiro checar com seu provedor, se voc� n�o tem certeza qual o seu servidor tem instalado.";
$lStep3_help       = "Ok, aqui voc� deve entrar com a informa��o do banco de dados.  Cada servidor possui diferentes configura��es, geralmente, se for um servidor virtual, a informa��o do Mysql/Pgsql ser� provida pelo seu painel de controle.  <br \><b>Server Name</b> � o nome do servidor ou o IP do computador que possui o banco de dados instalados.  Se voc� n�o puder encontrar isto no painel de controle ou no email de confirma��o de registro que foi enviado pelo servidor, � praticamente igual ao servidor local (localhost).  Outra maneira poder� ser o ip dentro do servidor ex.: 192.168.0.2.  Se o servidor notar que voc� tem usado uma porta que n�o seja a 3306, eu recomendo que voc� informe ela tamb�m, como [hostname]:[port] (Ex.: 192.168.0.2:2222).  <br \> O campo <b>Banco de Dados</b> informa ao phorum para selecionar um banco de dados para conectar a ele. O banco de dados geralmente precisa ser criado, se seu servidor permite m�ltiplos bancos de dados, voc� pode nome�-los como quiser, e certifiquesse que voc� informe o nome exato do banco de dados que voc� deseja utilizar.
Se o seu servidor n�o suporta m�ltiplos bancos de dados, provavelmente voc� dever� informar o mesmo login que voc� utiliza na conex�o ou o mesmo nome do dominio(Ex.:  Se seu dom�nios for exemplo.com, o nome do seu banco de dados poder� ser exemplo_com).  <br \><b>Nome de Usu�rio</b> e <b>Senha</b> s�o geralmente enviadas pelo servidor, de outra maneira eles s�o iguais ao usu�rio e senha utilizada no ftp. <br \><b>Nome da Tabela Principal</b> � a tabela onde a informa��o principal do phorum ser� gravada.  Ela precisa ser �nica (ex.: n�o pode existir outra tabela com o mesmo nome.)<br \><b>Atualia��o</b> Marcar a caixa de sele��o � obrigat�rio para quem deseja atualizar o phorum.";
$lStep4_help       = "Se voc� chegou at� aqui, voc� est� bem, seu banco dee dados est� funcionando e as tabelas est�o criadas.  Neste passo n�s iremos criar um usu�rio que ter� acesso total as fun��es de administrador.  Todos os campos s�o muito �bvios.  Se voc� est� atualizando o phorum com login de usu�rio (3.3 +) voc� pode entrar com seu usu�rio e senha, ent�o o phorum pode chegar se voc� � o administrador ou n�o, se o usu�rio que voc� informar estiver no banco de dados, mas n�o ser o administrador, a instala��o transformara para administrador, se ele j� � um administrador a instala��o n�o se preocupar� com administradores.";
$lStep5_help       = "�ltimo passo antes que voc� possa come�ar a adicionar f�runs e ativar recursos.  Aqui, voc� deve verificar se a<b>URL do Phorum</b> est� correta.  Aqui � suposto que seja o diret�rio do banco de dados do phorum (se algu�m digitar aqui, ele ter� a lista dos f�runs ou a lista dos t�picos, se isto acontecer ter� apenas um f�rum).  <br \>Ent�o, se isto for uma nova instala��o voc� precisar� entrar com o <b>endere�o de email do administrador</b> que ser� o endere�o que os usu�rios enviar�o suas mensagens posteriormente. Este endere�o padr�o de email ser� o que voc� receber� as d�vidas dos usu�rios.  <br \><b>Seu nome </b> estar� vis�vel apenas para as pessoas que est�o fazendo uma nova instala��o (Ex.: Administrator, Seu nome real, seu apelido, etc).  <br \>Se for uma atualiza��o, voc� ver�<b>Endere�o de Email do Phorum</b> ser� mostrado o endere�o do e-mail do administrador pelos f�runs aos usu�rios que tem permiss�o para mandar e-mail para voc�. Isto assume que o endere�o do administrador e este e-mail s�o os menos; mesmo que, voc� venha a trocar ele agora ele deve ser o do administrador";
// FINAL - texto de ajuda da instala��o
?>
