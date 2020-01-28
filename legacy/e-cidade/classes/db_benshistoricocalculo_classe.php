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

//MODULO: patrimonio
//CLASSE DA ENTIDADE benshistoricocalculo
class cl_benshistoricocalculo {
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
   var $t57_sequencial = 0;
   var $t57_mes = 0;
   var $t57_ano = 0;
   var $t57_datacalculo_dia = null;
   var $t57_datacalculo_mes = null;
   var $t57_datacalculo_ano = null;
   var $t57_datacalculo = null;
   var $t57_usuario = 0;
   var $t57_instituicao = 0;
   var $t57_tipocalculo = 0;
   var $t57_processado = 'f';
   var $t57_tipoprocessamento = 0;
   var $t57_ativo = 'f';
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 t57_sequencial = int4 = Sequencial
                 t57_mes = int4 = Mês de competência
                 t57_ano = int4 = Ano de competência
                 t57_datacalculo = date = Data
                 t57_usuario = int4 = Usuário
                 t57_instituicao = int4 = Instituição
                 t57_tipocalculo = int4 = Tipo do cálculo
                 t57_processado = bool = Processado
                 t57_tipoprocessamento = int4 = Tipo do processamento
                 t57_ativo = bool = Ativo
                 ";
   //funcao construtor da classe
   function cl_benshistoricocalculo() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("benshistoricocalculo");
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
       $this->t57_sequencial = ($this->t57_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["t57_sequencial"]:$this->t57_sequencial);
       $this->t57_mes = ($this->t57_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["t57_mes"]:$this->t57_mes);
       $this->t57_ano = ($this->t57_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["t57_ano"]:$this->t57_ano);
       if($this->t57_datacalculo == ""){
         $this->t57_datacalculo_dia = ($this->t57_datacalculo_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["t57_datacalculo_dia"]:$this->t57_datacalculo_dia);
         $this->t57_datacalculo_mes = ($this->t57_datacalculo_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["t57_datacalculo_mes"]:$this->t57_datacalculo_mes);
         $this->t57_datacalculo_ano = ($this->t57_datacalculo_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["t57_datacalculo_ano"]:$this->t57_datacalculo_ano);
         if($this->t57_datacalculo_dia != ""){
            $this->t57_datacalculo = $this->t57_datacalculo_ano."-".$this->t57_datacalculo_mes."-".$this->t57_datacalculo_dia;
         }
       }
       $this->t57_usuario = ($this->t57_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["t57_usuario"]:$this->t57_usuario);
       $this->t57_instituicao = ($this->t57_instituicao == ""?@$GLOBALS["HTTP_POST_VARS"]["t57_instituicao"]:$this->t57_instituicao);
       $this->t57_tipocalculo = ($this->t57_tipocalculo == ""?@$GLOBALS["HTTP_POST_VARS"]["t57_tipocalculo"]:$this->t57_tipocalculo);
       $this->t57_processado = ($this->t57_processado == "f"?@$GLOBALS["HTTP_POST_VARS"]["t57_processado"]:$this->t57_processado);
       $this->t57_tipoprocessamento = ($this->t57_tipoprocessamento == ""?@$GLOBALS["HTTP_POST_VARS"]["t57_tipoprocessamento"]:$this->t57_tipoprocessamento);
       $this->t57_ativo = ($this->t57_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["t57_ativo"]:$this->t57_ativo);
     }else{
       $this->t57_sequencial = ($this->t57_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["t57_sequencial"]:$this->t57_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($t57_sequencial){
      $this->atualizacampos();
     if($this->t57_mes == null ){
       $this->erro_sql = " Campo Mês de competência nao Informado.";
       $this->erro_campo = "t57_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t57_ano == null ){
       $this->erro_sql = " Campo Ano de competência nao Informado.";
       $this->erro_campo = "t57_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t57_datacalculo == null ){
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "t57_datacalculo_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t57_usuario == null ){
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "t57_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t57_instituicao == null ){
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "t57_instituicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t57_tipocalculo == null ){
       $this->erro_sql = " Campo Tipo do cálculo nao Informado.";
       $this->erro_campo = "t57_tipocalculo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t57_processado == null ){
       $this->erro_sql = " Campo Processado nao Informado.";
       $this->erro_campo = "t57_processado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t57_tipoprocessamento == null ){
       $this->erro_sql = " Campo Tipo do processamento nao Informado.";
       $this->erro_campo = "t57_tipoprocessamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t57_ativo == null ){
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "t57_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($t57_sequencial == "" || $t57_sequencial == null ){
       $result = db_query("select nextval('benshistoricocalculo_t57_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: benshistoricocalculo_t57_sequencial_seq do campo: t57_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->t57_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from benshistoricocalculo_t57_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $t57_sequencial)){
         $this->erro_sql = " Campo t57_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->t57_sequencial = $t57_sequencial;
       }
     }
     if(($this->t57_sequencial == null) || ($this->t57_sequencial == "") ){
       $this->erro_sql = " Campo t57_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into benshistoricocalculo(
                                       t57_sequencial
                                      ,t57_mes
                                      ,t57_ano
                                      ,t57_datacalculo
                                      ,t57_usuario
                                      ,t57_instituicao
                                      ,t57_tipocalculo
                                      ,t57_processado
                                      ,t57_tipoprocessamento
                                      ,t57_ativo
                       )
                values (
                                $this->t57_sequencial
                               ,$this->t57_mes
                               ,$this->t57_ano
                               ,".($this->t57_datacalculo == "null" || $this->t57_datacalculo == ""?"null":"'".$this->t57_datacalculo."'")."
                               ,$this->t57_usuario
                               ,$this->t57_instituicao
                               ,$this->t57_tipocalculo
                               ,'$this->t57_processado'
                               ,$this->t57_tipoprocessamento
                               ,'$this->t57_ativo'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Histórico cálculo depreciação ($this->t57_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Histórico cálculo depreciação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Histórico cálculo depreciação ($this->t57_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t57_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t57_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18556,'$this->t57_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3284,18556,'','".AddSlashes(pg_result($resaco,0,'t57_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3284,18557,'','".AddSlashes(pg_result($resaco,0,'t57_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3284,18558,'','".AddSlashes(pg_result($resaco,0,'t57_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3284,18559,'','".AddSlashes(pg_result($resaco,0,'t57_datacalculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3284,18560,'','".AddSlashes(pg_result($resaco,0,'t57_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3284,18561,'','".AddSlashes(pg_result($resaco,0,'t57_instituicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3284,18562,'','".AddSlashes(pg_result($resaco,0,'t57_tipocalculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3284,18578,'','".AddSlashes(pg_result($resaco,0,'t57_processado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3284,18564,'','".AddSlashes(pg_result($resaco,0,'t57_tipoprocessamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3284,18579,'','".AddSlashes(pg_result($resaco,0,'t57_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($t57_sequencial=null) {
      $this->atualizacampos();
     $sql = " update benshistoricocalculo set ";
     $virgula = "";
     if(trim($this->t57_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t57_sequencial"])){
       $sql  .= $virgula." t57_sequencial = $this->t57_sequencial ";
       $virgula = ",";
       if(trim($this->t57_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "t57_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t57_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t57_mes"])){
       $sql  .= $virgula." t57_mes = $this->t57_mes ";
       $virgula = ",";
       if(trim($this->t57_mes) == null ){
         $this->erro_sql = " Campo Mês de competência nao Informado.";
         $this->erro_campo = "t57_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t57_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t57_ano"])){
       $sql  .= $virgula." t57_ano = $this->t57_ano ";
       $virgula = ",";
       if(trim($this->t57_ano) == null ){
         $this->erro_sql = " Campo Ano de competência nao Informado.";
         $this->erro_campo = "t57_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t57_datacalculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t57_datacalculo_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["t57_datacalculo_dia"] !="") ){
       $sql  .= $virgula." t57_datacalculo = '$this->t57_datacalculo' ";
       $virgula = ",";
       if(trim($this->t57_datacalculo) == null ){
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "t57_datacalculo_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["t57_datacalculo_dia"])){
         $sql  .= $virgula." t57_datacalculo = null ";
         $virgula = ",";
         if(trim($this->t57_datacalculo) == null ){
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "t57_datacalculo_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->t57_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t57_usuario"])){
       $sql  .= $virgula." t57_usuario = $this->t57_usuario ";
       $virgula = ",";
       if(trim($this->t57_usuario) == null ){
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "t57_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t57_instituicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t57_instituicao"])){
       $sql  .= $virgula." t57_instituicao = $this->t57_instituicao ";
       $virgula = ",";
       if(trim($this->t57_instituicao) == null ){
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "t57_instituicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t57_tipocalculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t57_tipocalculo"])){
       $sql  .= $virgula." t57_tipocalculo = $this->t57_tipocalculo ";
       $virgula = ",";
       if(trim($this->t57_tipocalculo) == null ){
         $this->erro_sql = " Campo Tipo do cálculo nao Informado.";
         $this->erro_campo = "t57_tipocalculo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t57_processado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t57_processado"])){
       $sql  .= $virgula." t57_processado = '$this->t57_processado' ";
       $virgula = ",";
       if(trim($this->t57_processado) == null ){
         $this->erro_sql = " Campo Processado nao Informado.";
         $this->erro_campo = "t57_processado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t57_tipoprocessamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t57_tipoprocessamento"])){
       $sql  .= $virgula." t57_tipoprocessamento = $this->t57_tipoprocessamento ";
       $virgula = ",";
       if(trim($this->t57_tipoprocessamento) == null ){
         $this->erro_sql = " Campo Tipo do processamento nao Informado.";
         $this->erro_campo = "t57_tipoprocessamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t57_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t57_ativo"])){
       $sql  .= $virgula." t57_ativo = '$this->t57_ativo' ";
       $virgula = ",";
       if(trim($this->t57_ativo) == null ){
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "t57_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($t57_sequencial!=null){
       $sql .= " t57_sequencial = $this->t57_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t57_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18556,'$this->t57_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t57_sequencial"]) || $this->t57_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3284,18556,'".AddSlashes(pg_result($resaco,$conresaco,'t57_sequencial'))."','$this->t57_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t57_mes"]) || $this->t57_mes != "")
           $resac = db_query("insert into db_acount values($acount,3284,18557,'".AddSlashes(pg_result($resaco,$conresaco,'t57_mes'))."','$this->t57_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t57_ano"]) || $this->t57_ano != "")
           $resac = db_query("insert into db_acount values($acount,3284,18558,'".AddSlashes(pg_result($resaco,$conresaco,'t57_ano'))."','$this->t57_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t57_datacalculo"]) || $this->t57_datacalculo != "")
           $resac = db_query("insert into db_acount values($acount,3284,18559,'".AddSlashes(pg_result($resaco,$conresaco,'t57_datacalculo'))."','$this->t57_datacalculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t57_usuario"]) || $this->t57_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3284,18560,'".AddSlashes(pg_result($resaco,$conresaco,'t57_usuario'))."','$this->t57_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t57_instituicao"]) || $this->t57_instituicao != "")
           $resac = db_query("insert into db_acount values($acount,3284,18561,'".AddSlashes(pg_result($resaco,$conresaco,'t57_instituicao'))."','$this->t57_instituicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t57_tipocalculo"]) || $this->t57_tipocalculo != "")
           $resac = db_query("insert into db_acount values($acount,3284,18562,'".AddSlashes(pg_result($resaco,$conresaco,'t57_tipocalculo'))."','$this->t57_tipocalculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t57_processado"]) || $this->t57_processado != "")
           $resac = db_query("insert into db_acount values($acount,3284,18578,'".AddSlashes(pg_result($resaco,$conresaco,'t57_processado'))."','$this->t57_processado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t57_tipoprocessamento"]) || $this->t57_tipoprocessamento != "")
           $resac = db_query("insert into db_acount values($acount,3284,18564,'".AddSlashes(pg_result($resaco,$conresaco,'t57_tipoprocessamento'))."','$this->t57_tipoprocessamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t57_ativo"]) || $this->t57_ativo != "")
           $resac = db_query("insert into db_acount values($acount,3284,18579,'".AddSlashes(pg_result($resaco,$conresaco,'t57_ativo'))."','$this->t57_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Histórico cálculo depreciação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t57_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Histórico cálculo depreciação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t57_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t57_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($t57_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($t57_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18556,'$t57_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3284,18556,'','".AddSlashes(pg_result($resaco,$iresaco,'t57_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3284,18557,'','".AddSlashes(pg_result($resaco,$iresaco,'t57_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3284,18558,'','".AddSlashes(pg_result($resaco,$iresaco,'t57_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3284,18559,'','".AddSlashes(pg_result($resaco,$iresaco,'t57_datacalculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3284,18560,'','".AddSlashes(pg_result($resaco,$iresaco,'t57_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3284,18561,'','".AddSlashes(pg_result($resaco,$iresaco,'t57_instituicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3284,18562,'','".AddSlashes(pg_result($resaco,$iresaco,'t57_tipocalculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3284,18578,'','".AddSlashes(pg_result($resaco,$iresaco,'t57_processado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3284,18564,'','".AddSlashes(pg_result($resaco,$iresaco,'t57_tipoprocessamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3284,18579,'','".AddSlashes(pg_result($resaco,$iresaco,'t57_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from benshistoricocalculo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t57_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t57_sequencial = $t57_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Histórico cálculo depreciação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t57_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Histórico cálculo depreciação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t57_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t57_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:benshistoricocalculo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $t57_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from benshistoricocalculo ";
     $sql .= "      inner join db_config  on  db_config.codigo = benshistoricocalculo.t57_instituicao";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = benshistoricocalculo.t57_usuario";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if($dbwhere==""){
       if($t57_sequencial!=null ){
         $sql2 .= " where benshistoricocalculo.t57_sequencial = $t57_sequencial ";
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
   function sql_query_file ( $t57_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from benshistoricocalculo ";
     $sql2 = "";
     if($dbwhere==""){
       if($t57_sequencial!=null ){
         $sql2 .= " where benshistoricocalculo.t57_sequencial = $t57_sequencial ";
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

  function sql_query_historico_depreciacao( $t57_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {

    $sql = "select ";
    if ($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula = "";
      for ($i=0;$i<sizeof($campos_sql);$i++) {

        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from benshistoricocalculo ";
    $sql .= " inner join benshistoricocalculobem on t58_benshistoricocalculo = t57_sequencial   ";
    $sql .= " inner join bens 									 on t52_bem = t58_bens ";
    $sql .= " inner join clabens                 on t64_codcla = t52_codcla          ";
    $sql .= " inner join clabensconplano         on t86_clabens = t64_codcla";
    $sql .= " inner join bensdepreciacao         on t44_bens = t52_bem   ";
    $sql .= " inner join benstipodepreciacao     on t44_benstipodepreciacao = t46_sequencial   ";
    $sql .= " inner join db_depart 							 on coddepto = t52_depart ";
    $sql .= " left join bensbaix                 on t52_bem = t55_codbem       ";

    $sql2 = "";
    if($dbwhere==""){
      if($t57_sequencial!=null ){
        $sql2 .= " where benshistoricocalculo.t57_sequencial = $t57_sequencial ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    $sql .= " and t57_tipoprocessamento = 1";
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

  function sql_query_historico_depreciacao_iniciada ( $t57_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {

    $sql = "select ";
    if ($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula = "";
      for ($i=0;$i<sizeof($campos_sql);$i++) {

        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }

    $sql .= " from benshistoricocalculo ";
    $sql .= "      inner join benshistoricocalculobem on t58_benshistoricocalculo = t57_sequencial";
    $sql .= "      inner join bens 									  on t52_bem                  = t58_bens";
    $sql .= "      inner join clabens                 on t64_codcla               = t52_codcla";
    $sql .= "      inner join bensdepreciacao         on t44_bens                 = t52_bem";
    $sql .= "      inner join benstipodepreciacao     on t44_benstipodepreciacao  = t46_sequencial";
    $sql .= "      inner join db_depart 							on coddepto                 = t52_depart";
    $sql .= "      left  join bensbaix                on t52_bem                  = t55_codbem";

    $sql2 = "";
    if ($dbwhere == "") {

      if ($t57_sequencial != null ) {
        $sql2 .= " where benshistoricocalculo.t57_sequencial = $t57_sequencial ";
      }
    } else if($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }

    $sql .= $sql2;
    if ($ordem != null ) {

      $sql        .= " order by ";
      $campos_sql  = split("#",$ordem);
      $virgula     = "";

      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    }
    return $sql;
  }
}
?>