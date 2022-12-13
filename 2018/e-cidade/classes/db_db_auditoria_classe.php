<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_auditoria
class cl_db_auditoria {
   // cria variaveis de erro
   var $rotulo     = null;
   var $query_sql  = null;
   var $numrows    = 0;
   var $numrows_incluir = 0;
   var $numrows_alterar = 0;
   var $numrows_excluir = 0;
   var $erro_status= null;
   var $erro_sql   = null;
   var $erro_banco = null;
   var $erro_msg   = null;
   var $erro_campo = null;
   var $pagina_retorno = null;
   // cria variaveis do arquivo
   var $sequencial = 0;
   var $esquema = null;
   var $tabela = null;
   var $operacao = null;
   var $transacao = 0;
   var $datahora_sessao = 0;
   var $datahora_servidor = 0;
   var $tempo = null;
   var $usuario = null;
   var $chave = null;
   var $mudancas = null;
   var $logacessa = 0;
   var $instit = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 sequencial = int4 = Sequencial
                 esquema = text = Esquema
                 tabela = text = Tabela Pesquisa
                 operacao = text = Operação Executada
                 transacao = int8 = Código da Transação
                 datahora_sessao = int8 = Data da Sessao
                 datahora_servidor = int8 = Data do Servidor
                 tempo = varchar(20) = Tempo de Execução da Query
                 usuario = varchar(100) = Usuário
                 chave = varchar(200) = Chave Primária
                 mudancas = varchar(100) = Modificações efetuadas
                 logacessa = int4 = Código LogAcessa
                 instit = int4 = Instituição
                 ";
   //funcao construtor da classe
   function cl_db_auditoria() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_auditoria");
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro
   function erro($mostra,$retorna) {
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->sequencial = ($this->sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["sequencial"]:$this->sequencial);
       $this->esquema = ($this->esquema == ""?@$GLOBALS["HTTP_POST_VARS"]["esquema"]:$this->esquema);
       $this->tabela = ($this->tabela == ""?@$GLOBALS["HTTP_POST_VARS"]["tabela"]:$this->tabela);
       $this->operacao = ($this->operacao == ""?@$GLOBALS["HTTP_POST_VARS"]["operacao"]:$this->operacao);
       $this->transacao = ($this->transacao == ""?@$GLOBALS["HTTP_POST_VARS"]["transacao"]:$this->transacao);
       $this->datahora_sessao = ($this->datahora_sessao == ""?@$GLOBALS["HTTP_POST_VARS"]["datahora_sessao"]:$this->datahora_sessao);
       $this->datahora_servidor = ($this->datahora_servidor == ""?@$GLOBALS["HTTP_POST_VARS"]["datahora_servidor"]:$this->datahora_servidor);
       $this->tempo = ($this->tempo == ""?@$GLOBALS["HTTP_POST_VARS"]["tempo"]:$this->tempo);
       $this->usuario = ($this->usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["usuario"]:$this->usuario);
       $this->chave = ($this->chave == ""?@$GLOBALS["HTTP_POST_VARS"]["chave"]:$this->chave);
       $this->mudancas = ($this->mudancas == ""?@$GLOBALS["HTTP_POST_VARS"]["mudancas"]:$this->mudancas);
       $this->logacessa = ($this->logacessa == ""?@$GLOBALS["HTTP_POST_VARS"]["logacessa"]:$this->logacessa);
       $this->instit = ($this->instit == ""?@$GLOBALS["HTTP_POST_VARS"]["instit"]:$this->instit);
     }else{
       $this->sequencial = ($this->sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["sequencial"]:$this->sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($sequencial){
      $this->atualizacampos();
     if($this->esquema == null ){
       $this->erro_sql = " Campo Esquema nao Informado.";
       $this->erro_campo = "esquema";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tabela == null ){
       $this->erro_sql = " Campo Tabela Pesquisa nao Informado.";
       $this->erro_campo = "tabela";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->transacao == null ){
       $this->erro_sql = " Campo Código da Transação nao Informado.";
       $this->erro_campo = "transacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->datahora_sessao == null ){
       $this->erro_sql = " Campo Data da Sessao nao Informado.";
       $this->erro_campo = "datahora_sessao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->datahora_servidor == null ){
       $this->erro_sql = " Campo Data do Servidor nao Informado.";
       $this->erro_campo = "datahora_servidor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tempo == null ){
       $this->erro_sql = " Campo Tempo de Execução da Query nao Informado.";
       $this->erro_campo = "tempo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->chave == null ){
       $this->erro_sql = " Campo Chave Primária nao Informado.";
       $this->erro_campo = "chave";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->mudancas == null ){
       $this->erro_sql = " Campo Modificações efetuadas nao Informado.";
       $this->erro_campo = "mudancas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->logacessa == null ){
       $this->logacessa = "0";
     }
     if($this->instit == null ){
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sequencial == "" || $sequencial == null ){
       $result = db_query("select nextval('db_auditoria_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_auditoria_sequencial_seq do campo: sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from db_auditoria_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $sequencial)){
         $this->erro_sql = " Campo sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sequencial = $sequencial;
       }
     }
     if(($this->sequencial == null) || ($this->sequencial == "") ){
       $this->erro_sql = " Campo sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_auditoria(
                                       sequencial
                                      ,esquema
                                      ,tabela
                                      ,operacao
                                      ,transacao
                                      ,datahora_sessao
                                      ,datahora_servidor
                                      ,tempo
                                      ,usuario
                                      ,chave
                                      ,mudancas
                                      ,logacessa
                                      ,instit
                       )
                values (
                                $this->sequencial
                               ,'$this->esquema'
                               ,'$this->tabela'
                               ,'$this->operacao'
                               ,$this->transacao
                               ,$this->datahora_sessao
                               ,$this->datahora_servidor
                               ,'$this->tempo'
                               ,'$this->usuario'
                               ,'$this->chave'
                               ,'$this->mudancas'
                               ,$this->logacessa
                               ,$this->instit
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Autitoria ($this->sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Autitoria já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Autitoria ($this->sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8995,'$this->sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3398,8995,'','".AddSlashes(pg_result($resaco,0,'sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3398,19099,'','".AddSlashes(pg_result($resaco,0,'esquema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3398,19100,'','".AddSlashes(pg_result($resaco,0,'tabela'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3398,19101,'','".AddSlashes(pg_result($resaco,0,'operacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3398,19102,'','".AddSlashes(pg_result($resaco,0,'transacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3398,19103,'','".AddSlashes(pg_result($resaco,0,'datahora_sessao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3398,19104,'','".AddSlashes(pg_result($resaco,0,'datahora_servidor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3398,19105,'','".AddSlashes(pg_result($resaco,0,'tempo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3398,16019,'','".AddSlashes(pg_result($resaco,0,'usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3398,19107,'','".AddSlashes(pg_result($resaco,0,'chave'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3398,19108,'','".AddSlashes(pg_result($resaco,0,'mudancas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3398,19109,'','".AddSlashes(pg_result($resaco,0,'logacessa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3398,9996,'','".AddSlashes(pg_result($resaco,0,'instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($sequencial=null) {
      $this->atualizacampos();
     $sql = " update db_auditoria set ";
     $virgula = "";
     if(trim($this->sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sequencial"])){
       $sql  .= $virgula." sequencial = $this->sequencial ";
       $virgula = ",";
       if(trim($this->sequencial) == null ){
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->esquema)!="" || isset($GLOBALS["HTTP_POST_VARS"]["esquema"])){
       $sql  .= $virgula." esquema = '$this->esquema' ";
       $virgula = ",";
       if(trim($this->esquema) == null ){
         $this->erro_sql = " Campo Esquema nao Informado.";
         $this->erro_campo = "esquema";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tabela)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tabela"])){
       $sql  .= $virgula." tabela = '$this->tabela' ";
       $virgula = ",";
       if(trim($this->tabela) == null ){
         $this->erro_sql = " Campo Tabela Pesquisa nao Informado.";
         $this->erro_campo = "tabela";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->operacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["operacao"])){
       $sql  .= $virgula." operacao = '$this->operacao' ";
       $virgula = ",";
     }
     if(trim($this->transacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["transacao"])){
       $sql  .= $virgula." transacao = $this->transacao ";
       $virgula = ",";
       if(trim($this->transacao) == null ){
         $this->erro_sql = " Campo Código da Transação nao Informado.";
         $this->erro_campo = "transacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->datahora_sessao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["datahora_sessao"])){
       $sql  .= $virgula." datahora_sessao = $this->datahora_sessao ";
       $virgula = ",";
       if(trim($this->datahora_sessao) == null ){
         $this->erro_sql = " Campo Data da Sessao nao Informado.";
         $this->erro_campo = "datahora_sessao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->datahora_servidor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["datahora_servidor"])){
       $sql  .= $virgula." datahora_servidor = $this->datahora_servidor ";
       $virgula = ",";
       if(trim($this->datahora_servidor) == null ){
         $this->erro_sql = " Campo Data do Servidor nao Informado.";
         $this->erro_campo = "datahora_servidor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tempo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tempo"])){
       $sql  .= $virgula." tempo = '$this->tempo' ";
       $virgula = ",";
       if(trim($this->tempo) == null ){
         $this->erro_sql = " Campo Tempo de Execução da Query nao Informado.";
         $this->erro_campo = "tempo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["usuario"])){
       $sql  .= $virgula." usuario = '$this->usuario' ";
       $virgula = ",";
     }
     if(trim($this->chave)!="" || isset($GLOBALS["HTTP_POST_VARS"]["chave"])){
       $sql  .= $virgula." chave = '$this->chave' ";
       $virgula = ",";
       if(trim($this->chave) == null ){
         $this->erro_sql = " Campo Chave Primária nao Informado.";
         $this->erro_campo = "chave";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->mudancas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["mudancas"])){
       $sql  .= $virgula." mudancas = '$this->mudancas' ";
       $virgula = ",";
       if(trim($this->mudancas) == null ){
         $this->erro_sql = " Campo Modificações efetuadas nao Informado.";
         $this->erro_campo = "mudancas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->logacessa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["logacessa"])){
        if(trim($this->logacessa)=="" && isset($GLOBALS["HTTP_POST_VARS"]["logacessa"])){
           $this->logacessa = "0" ;
        }
       $sql  .= $virgula." logacessa = $this->logacessa ";
       $virgula = ",";
     }
     if(trim($this->instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["instit"])){
       $sql  .= $virgula." instit = $this->instit ";
       $virgula = ",";
       if(trim($this->instit) == null ){
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($sequencial!=null){
       $sql .= " sequencial = $this->sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8995,'$this->sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sequencial"]) || $this->sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3398,8995,'".AddSlashes(pg_result($resaco,$conresaco,'sequencial'))."','$this->sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["esquema"]) || $this->esquema != "")
           $resac = db_query("insert into db_acount values($acount,3398,19099,'".AddSlashes(pg_result($resaco,$conresaco,'esquema'))."','$this->esquema',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tabela"]) || $this->tabela != "")
           $resac = db_query("insert into db_acount values($acount,3398,19100,'".AddSlashes(pg_result($resaco,$conresaco,'tabela'))."','$this->tabela',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["operacao"]) || $this->operacao != "")
           $resac = db_query("insert into db_acount values($acount,3398,19101,'".AddSlashes(pg_result($resaco,$conresaco,'operacao'))."','$this->operacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["transacao"]) || $this->transacao != "")
           $resac = db_query("insert into db_acount values($acount,3398,19102,'".AddSlashes(pg_result($resaco,$conresaco,'transacao'))."','$this->transacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["datahora_sessao"]) || $this->datahora_sessao != "")
           $resac = db_query("insert into db_acount values($acount,3398,19103,'".AddSlashes(pg_result($resaco,$conresaco,'datahora_sessao'))."','$this->datahora_sessao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["datahora_servidor"]) || $this->datahora_servidor != "")
           $resac = db_query("insert into db_acount values($acount,3398,19104,'".AddSlashes(pg_result($resaco,$conresaco,'datahora_servidor'))."','$this->datahora_servidor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tempo"]) || $this->tempo != "")
           $resac = db_query("insert into db_acount values($acount,3398,19105,'".AddSlashes(pg_result($resaco,$conresaco,'tempo'))."','$this->tempo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["usuario"]) || $this->usuario != "")
           $resac = db_query("insert into db_acount values($acount,3398,16019,'".AddSlashes(pg_result($resaco,$conresaco,'usuario'))."','$this->usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["chave"]) || $this->chave != "")
           $resac = db_query("insert into db_acount values($acount,3398,19107,'".AddSlashes(pg_result($resaco,$conresaco,'chave'))."','$this->chave',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["mudancas"]) || $this->mudancas != "")
           $resac = db_query("insert into db_acount values($acount,3398,19108,'".AddSlashes(pg_result($resaco,$conresaco,'mudancas'))."','$this->mudancas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["logacessa"]) || $this->logacessa != "")
           $resac = db_query("insert into db_acount values($acount,3398,19109,'".AddSlashes(pg_result($resaco,$conresaco,'logacessa'))."','$this->logacessa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["instit"]) || $this->instit != "")
           $resac = db_query("insert into db_acount values($acount,3398,9996,'".AddSlashes(pg_result($resaco,$conresaco,'instit'))."','$this->instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Autitoria nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Autitoria nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8995,'$sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3398,8995,'','".AddSlashes(pg_result($resaco,$iresaco,'sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3398,19099,'','".AddSlashes(pg_result($resaco,$iresaco,'esquema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3398,19100,'','".AddSlashes(pg_result($resaco,$iresaco,'tabela'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3398,19101,'','".AddSlashes(pg_result($resaco,$iresaco,'operacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3398,19102,'','".AddSlashes(pg_result($resaco,$iresaco,'transacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3398,19103,'','".AddSlashes(pg_result($resaco,$iresaco,'datahora_sessao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3398,19104,'','".AddSlashes(pg_result($resaco,$iresaco,'datahora_servidor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3398,19105,'','".AddSlashes(pg_result($resaco,$iresaco,'tempo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3398,16019,'','".AddSlashes(pg_result($resaco,$iresaco,'usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3398,19107,'','".AddSlashes(pg_result($resaco,$iresaco,'chave'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3398,19108,'','".AddSlashes(pg_result($resaco,$iresaco,'mudancas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3398,19109,'','".AddSlashes(pg_result($resaco,$iresaco,'logacessa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3398,9996,'','".AddSlashes(pg_result($resaco,$iresaco,'instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_auditoria
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sequencial = $sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Autitoria nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Autitoria nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao do recordset
   function sql_record($sql) {
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:db_auditoria";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from db_auditoria ";
     $sql .= "      inner join db_config  on  db_config.codigo = db_auditoria.instit";
     $sql .= "      left  join db_usuarios  on  db_usuarios.id_usuario = db_auditoria.usuario";
     $sql .= "      left  join db_logsacessa  on  db_logsacessa.codsequen = db_auditoria.logacessa";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if($dbwhere==""){
       if($sequencial!=null ){
         $sql2 .= " where db_auditoria.sequencial = $sequencial ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   // funcao do sql
   function sql_query_file ( $sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from db_auditoria ";
     $sql2 = "";
     if($dbwhere==""){
       if($sequencial!=null ){
         $sql2 .= " where db_auditoria.sequencial = $sequencial ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }

  /**
   *
   * @param string $sCampos
   * @param string $iCodigoAcesso
   * @param array  $aParametros
   *               ["dDataInicio"] string  - "10/10/2000"
   *               ["dDataFim"]    string  - "10/10/2000"
   *               ["sHoraInicio"] string  - "00:00"
   *               ["sHoraFim"]    string  - "00:00"
   *               ["sUsuario"]    string  - Usuário a ser pesquisado
   *               ["iItemMenu"]   integer - Código do item de menu selecionado
   *               ["sEsquema"]    string  - Esquema selecionado
   *               ["sTabela"]     string  - Tabela selecionada
   *               ["sCampo"]      string  - Campo selecionado
   *               ["mValor"]              - Valor a ser pesquisado, seja antigo ou novo
   *               ["iTipoAcesso"] integer
   *                               0 - Todas
   *                               1 - Apenas Acesso a Rotina
   *                               2 - Acesso a Rotina com Modificações no Sistema
   *
   * @return string
   */
  function sql_query_acessos( $sCampos = null, $iCodigoAcesso = null, $aParametros ) {

    $iInstituicao = db_getsession("DB_instit");
    if ( empty($sCampos) ) {
      $sCampos = 'db_logsacessa.*';
    }

    /**
     * Valores padrão para data de início/fim e hora de início/fim. Caso tenha sido informado algum valor na tela,
     * este substituem os valores padrões
     */
    $dDataInicio = $aParametros["dDataInicio"];
    $dDataFim    = $aParametros["dDataFim"];
    $sHoraInicio = '00:00:00.000000';
    $sHoraFim    = '23:59:59.999999';

    if ( array_key_exists( "sHoraInicio", $aParametros ) && !empty( $aParametros["sHoraInicio"] ) ) {
      $sHoraInicio = $aParametros["sHoraInicio"].".000000";
    }

    if ( array_key_exists( "sHoraFim", $aParametros ) && !empty( $aParametros["sHoraFim"] ) ) {
      $sHoraFim = $aParametros["sHoraFim"].".999999";
    }

    /**
     * Campos necessários para pesquisar e filtrar os dados na PL das modificações
     */
    $sLeft       = "";
    $sEsquema    = "null";
    $sTabela     = "null";
    $sUsuario    = "null";
    $sCampo      = "null";
    $mValor      = "null";
    $lTemEsquema = false;

    if ( array_key_exists( "sEsquema", $aParametros ) && !empty( $aParametros["sEsquema"] ) ) {

      $sEsquema    = "'{$aParametros["sEsquema"]}'";
      $lTemEsquema = true;
    }

    if ( array_key_exists( "sTabela", $aParametros ) && !empty( $aParametros["sTabela"] ) ) {
      $sTabela = "'{$aParametros["sTabela"]}'";
    }

    if ( array_key_exists( "sUsuario", $aParametros ) && !empty( $aParametros["sUsuario"] ) ) {
      $sUsuario = "'{$aParametros["sUsuario"]}'";
    }

    if ( array_key_exists( "sCampo", $aParametros ) && !empty( $aParametros["sCampo"] ) ) {

      $sCampo = "'{$aParametros["sCampo"]}'";

      if ( array_key_exists( "mValor", $aParametros ) && !empty( $aParametros["mValor"] ) ) {
        $mValor = "'{$aParametros["mValor"]}'";
      }
    }

    $sWhere = "1=1 ";

    if ($aParametros["iTipoAcesso"] == 1) {
      $sWhere .= "   and db_logsacessa.auditoria is false \n";
    }

    if ($aParametros["iTipoAcesso"] == 2) {
      $sWhere .= "   and db_logsacessa.auditoria is true \n";
    }

    if ( array_key_exists( "iUsuario", $aParametros ) && !empty( $aParametros["iUsuario"] ) ) {
      $sWhere .= "   and db_logsacessa.id_usuario = {$aParametros["iUsuario"]}                                       \n";
    }

    if ( array_key_exists( "iItemMenu", $aParametros ) && !empty( $aParametros["iItemMenu"] ) ) {
      $sWhere .= "   and db_logsacessa.id_item    = {$aParametros["iItemMenu"]}                                      \n";
    }

    if ( array_key_exists( "iModulo", $aParametros ) && !empty( $aParametros["iModulo"] ) ) {

      $sWhere .= "   and db_logsacessa.id_modulo    = {$aParametros["iModulo"]}                                      \n";
    }


    /**
     * Substitui anteriores
     */
    if ( !empty($iCodigoAcesso) ) {
      $sWhere = "   and db_logsacessa.codsequen = {$iCodigoAcesso}                                           \n";
    }

    $sSql  = "with db_logsacessa (codsequen, ip, data, hora, arquivo, obs, id_usuario, id_modulo, id_item, coddepto, instit, auditoria) as ( ";
    $sSql .= " select * from fc_logsacessa_consulta('{$dDataInicio} {$sHoraInicio}', '{$dDataFim} {$sHoraFim}', {$iInstituicao}, '$sWhere') ";
    $sSql .= ") ";
    $sSql .= "select {$sCampos},                                 \n";
    $sSql .= "       db_itensmenu.descricao as descricao_menu,  \n";
    $sSql .= "       db_logsacessa.auditoria as modificacoes    \n";

    /**
     * Se tem esquema é porque os usuario selecinou consulta tipo 2
     * e informou os dados dos filtros avancados
     */
    if ( $lTemEsquema ) {

      $sSql .= "FROM configuracoes.fc_auditoria_consulta_acessos('{$dDataInicio} {$sHoraInicio}', \n";
      $sSql .= "                                                 '{$dDataFim} {$sHoraFim}'      , \n";
      $sSql .= "                                                 {$sEsquema}                    , \n";
      $sSql .= "                                                 {$sTabela}                     , \n";
      $sSql .= "                                                 {$sUsuario}                    , \n";
      $sSql .= "                                                 {$iInstituicao}                , \n";
      $sSql .= "                                                 {$sCampo}                      , \n";
      $sSql .= "                                                 {$mValor}                      , \n";
      $sSql .= "                                                 {$mValor}) as logsacessa         \n";

      $sSql .= "JOIN db_logsacessa ON codsequen              = logsacessa                        \n";
      $sSql .= "JOIN db_usuarios   ON db_usuarios.id_usuario = db_logsacessa.id_usuario          \n";
      $sSql .= "JOIN db_itensmenu  ON db_itensmenu.id_item   = db_logsacessa.id_item             \n";

    } else {

      $sSql .= "  from db_logsacessa                                                                                    \n";
      $sSql .= "       inner join db_usuarios  on db_usuarios.id_usuario = db_logsacessa.id_usuario                     \n";
      $sSql .= "       inner join db_itensmenu on db_itensmenu.id_item   = db_logsacessa.id_item                        \n";

      $sWhere .= " and db_logsacessa.instit     = {$iInstituicao}                           \n";
      $sWhere .= " and db_logsacessa.data between '{$dDataInicio}' and '{$dDataFim}'        \n";
    }

    $sSql .= "order by data, hora, login";

    return $sSql;
  }

  function sql_query_modificacoes(Array $aParametros) {

    $sDataHoraInicial = 'null';
    $sDataHoraFim     = 'null';
    $sEsquema         = 'null';
    $sTabela          = 'null';
    $sUsuario         = 'null';
    $iCodigoAcesso    = 'null';
    $iInstituicao     = 'null';
    $sCampo           = 'null';
    $sValorAntigo     = 'null';
    $sValorNovo       = 'null';

    if (!empty($aParametros['sDataHoraInicial'])) {
      $sDataHoraInicial = "'{$aParametros['sDataHoraInicial']}'";
    }

    if (!empty($aParametros['sDataHoraFim'])) {
      $sDataHoraFim = "'{$aParametros['sDataHoraFim']}'";
    }

    if (!empty($aParametros['sEsquema'])) {
      $sEsquema = "'{$aParametros['sEsquema']}'";
    }

    if (!empty($aParametros['sTabela'])) {
      $sTabela = "'{$aParametros['sTabela']}'";
    }

    if (!empty($aParametros['sUsuario'])) {
      $sUsuario = "'{$aParametros['sUsuario']}'";
    }

    if (!empty($aParametros['iCodigoAcesso'])) {
      $iCodigoAcesso = $aParametros['iCodigoAcesso'];
    }

    if (!empty($aParametros['iInstituicao'])) {
      $iInstituicao = $aParametros['iInstituicao'];
    }

    if (!empty($aParametros['sCampo'])) {
      $sCampo = "'{$aParametros['sCampo']}'";
    }

    if (!empty($aParametros['mValor'])) {

      $sValorAntigo = "'{$aParametros['mValor']}'";
      $sValorNovo = "'{$aParametros['mValor']}'";
    }

    $sParametrosConsulta  = "$sDataHoraInicial, $sDataHoraFim, $sEsquema, $sTabela, $sUsuario,";
    $sParametrosConsulta .= "$iCodigoAcesso, $iInstituicao, $sCampo, $sValorAntigo, $sValorNovo";

    $sSql = "select                                                                                    \n";
    $sSql.= "       db_sysarquivo.codarq,                                                              \n";
    $sSql.= "       fc_auditoria.tabela,                                                               \n";
    $sSql.= "       db_sysarquivo.rotulo,                                                              \n";
    $sSql.= "       db_sysarqcamp.seqarq,                                                              \n";
    $sSql.= "       db_syscampo.nomecam,                                                               \n";
    $sSql.= "       fc_auditoria.nome_campo,                                                           \n";
    $sSql.= "       fc_auditoria.operacao,                                                             \n";
    $sSql.= "       fc_auditoria.logsacessa,                                                           \n";
    $sSql.= "       fc_auditoria.valor_antigo,                                                         \n";
    $sSql.= "       fc_auditoria.valor_novo,                                                           \n";
    $sSql.= "       fc_auditoria.datahora_servidor                                                     \n";
    $sSql.= "  from configuracoes.fc_auditoria_consulta_mudancas($sParametrosConsulta) AS fc_auditoria \n";
    $sSql.= "       left join db_syscampo    on nome_campo           = trim(nomecam)                   \n";
    $sSql.= "       left join db_sysarquivo  on tabela               = trim(nomearq)                   \n";
    $sSql.= "       left join db_sysarqcamp  on db_sysarqcamp.codarq = db_sysarquivo.codarq            \n";
    $sSql.= "                               and db_syscampo.codcam   = db_sysarqcamp.codcam            \n";
    $sSql.= " order by fc_auditoria.datahora_servidor,                                                 \n";
    $sSql.= "          fc_auditoria.tabela,                                                            \n";
    $sSql.= "          fc_auditoria.operacao,                                                          \n";
    $sSql.= "          db_sysarqcamp.seqarq,                                                           \n";
    $sSql.= "          fc_auditoria.logsacessa                                                         \n";

    return $sSql;
  }

}
