<?php
// arquivo-de-lingua para area de administração do phorum-3.4.x

##############################################################
# Traduzido por: Gabriel Butzke                              #
# E-mail: gb@unerj.br                                        #
# Data: 13/03/2003                                           #
# Ajuda: Coloque este arquivo na pasta admin/lang do Phorum  #
#        e quando for instalá-lo, no passo 1 em              #
#        Select Installation-Language: selecione português.  #
##############################################################

// INÍCIO - variáveis de instalação do fórum:
$lTitle            = "Instalação do Phorum";
$lAdmin_Perm_Check = "-<b>Checando permissões:</b><br />";
$lAdmin_Perm_Ok    = " está <font class='check_ok'>[OK]</font><br \>";
$lAdmin_Perm_Bad   = " está <font class='check_bad'>[Ruim]</font><br \><font class=error>Arrume: mude permissões para 777 ou 666</font><br />";
$lHelp             = "ajuda";
$lNextStep	   = "-Próximo Passo-";
$lFinish           = "-Passo Final-";
$lStep1_end        = "Passo 1: Completo.<br \>";
$lStep2		   = "Passo 2: Tipo do Banco de Dados.";
$lDBType	   = "Entre o Tipo do Banco de Dados:";
$lStep2_end	   = "Passo 2: Completo.<br \>";
$lStep3		   = "Passo 3: Configurações do Banco de Dados.";
$lStep3_end	   = "<br \>Passo 3: Completo.<br \>";
$lStep4            = "Passo 4: Administrador.";
$lStep4_end	   = "Passo 4: Completo.<br \>";
$lStep5            = "Passo 5: Último Passo.";
$lDBServer	   = "Banco de Dados - Nome do Servidor:";
$lDBName	   = "Banco de Dados - Nome:";
$lDBUser	   = "Banco de Dados - Nome do Usuário:";
$lDBPass	   = "Banco de Dados - Senha:";
$lPhorumTable      = "Phorum - Nome da Tabela Principal:";
$lUpdate           = "Selecione se isto é uma atualização.<br />Leia docs/upgrade.txt para maiores informações sobre suas configurações.";
$lAttachmentDir    = "Se você preferir atualizar atachamentos também, apenas entre com o caminho do diretório aqui como estava na sua antiga instalação.";
$lDBNote	   = "Obs.:  Se o SQL Safe Mode está ativado no seu servidor, deixe o usuário e a senha em branco.";
$lAdminUser        = "Nome do Usuário:";
$lAdminPass        = "Senha:";
$lAdminPass2       = "(novamente)";
$lPhorum_URL       = "Phorum URL:";
$lAdminEmail       = "Email do Administrador:";
$lDefaultEmail     = "Email do Phorum:";
$lAdminName        = "Mostre o nome:";
$lErrorFile        = "Não foi possível abrir ou encontrar o banco de dados. Verifique se você informou o diretório do banco de dados.";
$lErrorDB	   = "Não pode conectar o banco de dados.  Verifique suas configurações novamente.";
$lErrorTables      = "O Mysql não pode criar as tabelas. Talvez seja para atualizar?";
$lErrorFields      = "Por favor preencha todos os campos";
$lErrorPass        = "Senha Inválida";
$lErrorWrongPass   = "Usuário existente, mas senha está incorreta.";
$lErrorDBAdmin     = "Não pode criar usuário administrador. Banco de dados retornou: ";
$lErrorURL         = "Não é uma URL válida";
$lErrorEmail       = "Não é um endereço de email válido";
$lErrorName        = "Nome está em branco";
$lDB_Ok            = "Configurações do Banco de Dados estão corretas!<br />\n";
$lDB_Upgrade       = "Atualizando tabelas...<br />";
$lDB_Create        = "Criando tabelas iniciais...<br />\n";
$lDB_Create_done   = "<b>Tabelas Criadas!</b><br />\n";
$lUserExists       = "Usuário está no banco de dados...<br />\n";
$lUserIsAdmin      = "$AdminUser já é um administrador.<br />\n";
$lUserAdmin        = "$AdminUser não era um administrador, mas agora é :)<br />\n";
$lAdminCreated     = "Usuário Administrador Criado<br />\n";
$lFINAL            = "Parabéns!  <a href=\"$PHP_SELF\">Clique aqui</a> para ir para a parte de administração do Phorum.";
// FINAL - variáveis de instalação do fórum;
// INÍCIO - texto de ajuda da instalação
$lHelpTitle        = "Ajuda para Instalação do Phorum";
$lCloseWindow      = "Fechar.";
$lStep1_help       = "Seja Bem-Vindo ao Script de Instalação do Phourm. Eu tentarei ajudar os iniciantes ao Phorum através da instalação.  Tudo que você precisa fazer é ir até o passo 1 escolher a língua da sua instalação.";
$lStep2_help       = "No topo da tela de cada instalação você verá a informação sobre a ação que acontecerá após você clicar no botão.  No último passo, phorum verfica se as permissoes para os arquivos que o phorum utilizará estão corretas.  Se você ver <font class='check_bad'>[Bad]</font> próximo ao forums.php ou diretório de configurações, você terá que utilizar o CHMOD ou um programa de FTP para alterar a permissão 777 ou 666 (qualquer uma funcionará, mas permissões 666 provavelmente não funcionarão em alguns servidores). Isto é geralmente feito com um programa de cliente de FTP como por exemplo SmartFTP clique em file/dir e escolhendo \"Fie Permissions\" ou \"Chmod\" command.  Você não precisa apertar o botão de \"Atualizar\" do seu navegador se você tem certeza que selecionou as permissoes corretas, mas se você não tem certeza, o Phorum checará novamente as permissões depois que você atualizar o seu navegador.<br \><br \> Depois que você organizar as permissões, é hora de escolher o banco de dados que seu servidor possui.  O Phorum suporta MySQL e PostgreSQL.  MySQL é o mais utilizado, mas eu sugiro checar com seu provedor, se você não tem certeza qual o seu servidor tem instalado.";
$lStep3_help       = "Ok, aqui você deve entrar com a informação do banco de dados.  Cada servidor possui diferentes configurações, geralmente, se for um servidor virtual, a informação do Mysql/Pgsql será provida pelo seu painel de controle.  <br \><b>Server Name</b> é o nome do servidor ou o IP do computador que possui o banco de dados instalados.  Se você não puder encontrar isto no painel de controle ou no email de confirmação de registro que foi enviado pelo servidor, é praticamente igual ao servidor local (localhost).  Outra maneira poderá ser o ip dentro do servidor ex.: 192.168.0.2.  Se o servidor notar que você tem usado uma porta que não seja a 3306, eu recomendo que você informe ela também, como [hostname]:[port] (Ex.: 192.168.0.2:2222).  <br \> O campo <b>Banco de Dados</b> informa ao phorum para selecionar um banco de dados para conectar a ele. O banco de dados geralmente precisa ser criado, se seu servidor permite múltiplos bancos de dados, você pode nomeá-los como quiser, e certifiquesse que você informe o nome exato do banco de dados que você deseja utilizar.
Se o seu servidor não suporta múltiplos bancos de dados, provavelmente você deverá informar o mesmo login que você utiliza na conexão ou o mesmo nome do dominio(Ex.:  Se seu domínios for exemplo.com, o nome do seu banco de dados poderá ser exemplo_com).  <br \><b>Nome de Usuário</b> e <b>Senha</b> são geralmente enviadas pelo servidor, de outra maneira eles são iguais ao usuário e senha utilizada no ftp. <br \><b>Nome da Tabela Principal</b> é a tabela onde a informação principal do phorum será gravada.  Ela precisa ser única (ex.: não pode existir outra tabela com o mesmo nome.)<br \><b>Atualiação</b> Marcar a caixa de seleção é obrigatório para quem deseja atualizar o phorum.";
$lStep4_help       = "Se você chegou até aqui, você está bem, seu banco dee dados está funcionando e as tabelas estão criadas.  Neste passo nós iremos criar um usuário que terá acesso total as funções de administrador.  Todos os campos são muito óbvios.  Se você está atualizando o phorum com login de usuário (3.3 +) você pode entrar com seu usuário e senha, então o phorum pode chegar se você é o administrador ou não, se o usuário que você informar estiver no banco de dados, mas não ser o administrador, a instalação transformara para administrador, se ele já é um administrador a instalação não se preocupará com administradores.";
$lStep5_help       = "Último passo antes que você possa começar a adicionar fóruns e ativar recursos.  Aqui, você deve verificar se a<b>URL do Phorum</b> está correta.  Aqui é suposto que seja o diretório do banco de dados do phorum (se alguém digitar aqui, ele terá a lista dos fóruns ou a lista dos tópicos, se isto acontecer terá apenas um fórum).  <br \>Então, se isto for uma nova instalação você precisará entrar com o <b>endereço de email do administrador</b> que será o endereço que os usuários enviarão suas mensagens posteriormente. Este endereço padrão de email será o que você receberá as dúvidas dos usuários.  <br \><b>Seu nome </b> estará visível apenas para as pessoas que estão fazendo uma nova instalação (Ex.: Administrator, Seu nome real, seu apelido, etc).  <br \>Se for uma atualização, você verá<b>Endereço de Email do Phorum</b> será mostrado o endereço do e-mail do administrador pelos fóruns aos usuários que tem permissão para mandar e-mail para você. Isto assume que o endereço do administrador e este e-mail são os menos; mesmo que, você venha a trocar ele agora ele deve ser o do administrador";
// FINAL - texto de ajuda da instalação
?>
