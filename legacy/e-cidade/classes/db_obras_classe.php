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

//MODULO: projetos
//CLASSE DA ENTIDADE obras
class cl_obras {
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
   var $ob01_codobra = 0;
   var $ob01_nomeobra = null;
   var $ob01_tiporesp = 0;
   var $ob01_regular = 'f';
   var $ob01_dtobra_dia = null;
   var $ob01_dtobra_mes = null;
   var $ob01_dtobra_ano = null;
   var $ob01_dtobra = null;
   var $ob01_processo = null;
   var $ob01_nometitularproc = null;
   var $ob01_dtprocesso_dia = null;
   var $ob01_dtprocesso_mes = null;
   var $ob01_dtprocesso_ano = null;
   var $ob01_dtprocesso = null;
   var $ob01_obs = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ob01_codobra = int4 = Código da obra
                 ob01_nomeobra = varchar(55) = Nome da obra
                 ob01_tiporesp = int4 = Código do tipo de responsável
                 ob01_regular = bool = Obra Regular
                 ob01_dtobra = date = Data Obra
                 ob01_processo = varchar(40) = Código Processo
                 ob01_nometitularproc = varchar(40) = Nome Titular
                 ob01_dtprocesso = date = Data Processo
                 ob01_obs = text = Observações
                 ";
   //funcao construtor da classe
   function cl_obras() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("obras");
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
       $this->ob01_codobra = ($this->ob01_codobra == ""?@$GLOBALS["HTTP_POST_VARS"]["ob01_codobra"]:$this->ob01_codobra);
       $this->ob01_nomeobra = ($this->ob01_nomeobra == ""?@$GLOBALS["HTTP_POST_VARS"]["ob01_nomeobra"]:$this->ob01_nomeobra);
       $this->ob01_tiporesp = ($this->ob01_tiporesp == ""?@$GLOBALS["HTTP_POST_VARS"]["ob01_tiporesp"]:$this->ob01_tiporesp);
       $this->ob01_regular = ($this->ob01_regular == "f"?@$GLOBALS["HTTP_POST_VARS"]["ob01_regular"]:$this->ob01_regular);
       if($this->ob01_dtobra == ""){
         $this->ob01_dtobra_dia = ($this->ob01_dtobra_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ob01_dtobra_dia"]:$this->ob01_dtobra_dia);
         $this->ob01_dtobra_mes = ($this->ob01_dtobra_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ob01_dtobra_mes"]:$this->ob01_dtobra_mes);
         $this->ob01_dtobra_ano = ($this->ob01_dtobra_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ob01_dtobra_ano"]:$this->ob01_dtobra_ano);
         if($this->ob01_dtobra_dia != ""){
            $this->ob01_dtobra = $this->ob01_dtobra_ano."-".$this->ob01_dtobra_mes."-".$this->ob01_dtobra_dia;
         }
       }
       $this->ob01_processo = ($this->ob01_processo == ""?@$GLOBALS["HTTP_POST_VARS"]["ob01_processo"]:$this->ob01_processo);
       $this->ob01_nometitularproc = ($this->ob01_nometitularproc == ""?@$GLOBALS["HTTP_POST_VARS"]["ob01_nometitularproc"]:$this->ob01_nometitularproc);
       if($this->ob01_dtprocesso == ""){
         $this->ob01_dtprocesso_dia = ($this->ob01_dtprocesso_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ob01_dtprocesso_dia"]:$this->ob01_dtprocesso_dia);
         $this->ob01_dtprocesso_mes = ($this->ob01_dtprocesso_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ob01_dtprocesso_mes"]:$this->ob01_dtprocesso_mes);
         $this->ob01_dtprocesso_ano = ($this->ob01_dtprocesso_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ob01_dtprocesso_ano"]:$this->ob01_dtprocesso_ano);
         if($this->ob01_dtprocesso_dia != ""){
            $this->ob01_dtprocesso = $this->ob01_dtprocesso_ano."-".$this->ob01_dtprocesso_mes."-".$this->ob01_dtprocesso_dia;
         }
       }
       $this->ob01_obs = ($this->ob01_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ob01_obs"]:$this->ob01_obs);
     }else{
       $this->ob01_codobra = ($this->ob01_codobra == ""?@$GLOBALS["HTTP_POST_VARS"]["ob01_codobra"]:$this->ob01_codobra);
     }
   }
   // funcao para inclusao
   function incluir ($ob01_codobra){
      $this->atualizacampos();
     if($this->ob01_nomeobra == null ){
       $this->erro_sql = " Campo Nome da obra nao Informado.";
       $this->erro_campo = "ob01_nomeobra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob01_tiporesp == null ){
       $this->erro_sql = " Campo Código do tipo de responsável nao Informado.";
       $this->erro_campo = "ob01_tiporesp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob01_regular == null ){
       $this->erro_sql = " Campo Obra Regular nao Informado.";
       $this->erro_campo = "ob01_regular";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob01_dtobra == null ){
       $this->erro_sql = " Campo Data Obra nao Informado.";
       $this->erro_campo = "ob01_dtobra_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob01_processo == null ){
       $this->ob01_processo = "0";
     }
     if($this->ob01_dtprocesso == null ){
       $this->ob01_dtprocesso = "null";
     }
     if($ob01_codobra == "" || $ob01_codobra == null ){
       $result = db_query("select nextval('obras_ob01_codobra_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: obras_ob01_codobra_seq do campo: ob01_codobra";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ob01_codobra = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from obras_ob01_codobra_seq");
       if(($result != false) && (pg_result($result,0,0) < $ob01_codobra)){
         $this->erro_sql = " Campo ob01_codobra maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ob01_codobra = $ob01_codobra;
       }
     }
     if(($this->ob01_codobra == null) || ($this->ob01_codobra == "") ){
       $this->erro_sql = " Campo ob01_codobra nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into obras(
                                       ob01_codobra
                                      ,ob01_nomeobra
                                      ,ob01_tiporesp
                                      ,ob01_regular
                                      ,ob01_dtobra
                                      ,ob01_processo
                                      ,ob01_nometitularproc
                                      ,ob01_dtprocesso
                                      ,ob01_obs
                       )
                values (
                                $this->ob01_codobra
                               ,'$this->ob01_nomeobra'
                               ,$this->ob01_tiporesp
                               ,'$this->ob01_regular'
                               ,".($this->ob01_dtobra == "null" || $this->ob01_dtobra == ""?"null":"'".$this->ob01_dtobra."'")."
                               ,'$this->ob01_processo'
                               ,'$this->ob01_nometitularproc'
                               ,".($this->ob01_dtprocesso == "null" || $this->ob01_dtprocesso == ""?"null":"'".$this->ob01_dtprocesso."'")."
                               ,'$this->ob01_obs'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cadastro de obras ($this->ob01_codobra) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cadastro de obras já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cadastro de obras ($this->ob01_codobra) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ob01_codobra;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ob01_codobra));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5909,'$this->ob01_codobra','I')");
       $resac = db_query("insert into db_acount values($acount,946,5909,'','".AddSlashes(pg_result($resaco,0,'ob01_codobra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,946,5910,'','".AddSlashes(pg_result($resaco,0,'ob01_nomeobra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,946,5913,'','".AddSlashes(pg_result($resaco,0,'ob01_tiporesp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,946,5914,'','".AddSlashes(pg_result($resaco,0,'ob01_regular'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,946,18629,'','".AddSlashes(pg_result($resaco,0,'ob01_dtobra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,946,18630,'','".AddSlashes(pg_result($resaco,0,'ob01_processo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,946,18631,'','".AddSlashes(pg_result($resaco,0,'ob01_nometitularproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,946,18632,'','".AddSlashes(pg_result($resaco,0,'ob01_dtprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,946,18633,'','".AddSlashes(pg_result($resaco,0,'ob01_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ob01_codobra=null) {
      $this->atualizacampos();
     $sql = " update obras set ";
     $virgula = "";
     if(trim($this->ob01_codobra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob01_codobra"])){
       $sql  .= $virgula." ob01_codobra = $this->ob01_codobra ";
       $virgula = ",";
       if(trim($this->ob01_codobra) == null ){
         $this->erro_sql = " Campo Código da obra nao Informado.";
         $this->erro_campo = "ob01_codobra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob01_nomeobra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob01_nomeobra"])){
       $sql  .= $virgula." ob01_nomeobra = '$this->ob01_nomeobra' ";
       $virgula = ",";
       if(trim($this->ob01_nomeobra) == null ){
         $this->erro_sql = " Campo Nome da obra nao Informado.";
         $this->erro_campo = "ob01_nomeobra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob01_tiporesp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob01_tiporesp"])){
       $sql  .= $virgula." ob01_tiporesp = $this->ob01_tiporesp ";
       $virgula = ",";
       if(trim($this->ob01_tiporesp) == null ){
         $this->erro_sql = " Campo Código do tipo de responsável nao Informado.";
         $this->erro_campo = "ob01_tiporesp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob01_regular)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob01_regular"])){
       $sql  .= $virgula." ob01_regular = '$this->ob01_regular' ";
       $virgula = ",";
       if(trim($this->ob01_regular) == null ){
         $this->erro_sql = " Campo Obra Regular nao Informado.";
         $this->erro_campo = "ob01_regular";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob01_dtobra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob01_dtobra_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ob01_dtobra_dia"] !="") ){
       $sql  .= $virgula." ob01_dtobra = '$this->ob01_dtobra' ";
       $virgula = ",";
       if(trim($this->ob01_dtobra) == null ){
         $this->erro_sql = " Campo Data Obra nao Informado.";
         $this->erro_campo = "ob01_dtobra_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ob01_dtobra_dia"])){
         $sql  .= $virgula." ob01_dtobra = null ";
         $virgula = ",";
         if(trim($this->ob01_dtobra) == null ){
           $this->erro_sql = " Campo Data Obra nao Informado.";
           $this->erro_campo = "ob01_dtobra_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ob01_processo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob01_processo"])){
       $sql  .= $virgula." ob01_processo = '$this->ob01_processo' ";
       $virgula = ",";
     }
     if(trim($this->ob01_nometitularproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob01_nometitularproc"])){
       $sql  .= $virgula." ob01_nometitularproc = '$this->ob01_nometitularproc' ";
       $virgula = ",";
     }
     if(trim($this->ob01_dtprocesso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob01_dtprocesso_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ob01_dtprocesso_dia"] !="") ){
       $sql  .= $virgula." ob01_dtprocesso = '$this->ob01_dtprocesso' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ob01_dtprocesso_dia"])){
         $sql  .= $virgula." ob01_dtprocesso = null ";
         $virgula = ",";
       }
     }
     if(trim($this->ob01_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob01_obs"])){
       $sql  .= $virgula." ob01_obs = '$this->ob01_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ob01_codobra!=null){
       $sql .= " ob01_codobra = $this->ob01_codobra";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ob01_codobra));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5909,'$this->ob01_codobra','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob01_codobra"]) || $this->ob01_codobra != "")
           $resac = db_query("insert into db_acount values($acount,946,5909,'".AddSlashes(pg_result($resaco,$conresaco,'ob01_codobra'))."','$this->ob01_codobra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob01_nomeobra"]) || $this->ob01_nomeobra != "")
           $resac = db_query("insert into db_acount values($acount,946,5910,'".AddSlashes(pg_result($resaco,$conresaco,'ob01_nomeobra'))."','$this->ob01_nomeobra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob01_tiporesp"]) || $this->ob01_tiporesp != "")
           $resac = db_query("insert into db_acount values($acount,946,5913,'".AddSlashes(pg_result($resaco,$conresaco,'ob01_tiporesp'))."','$this->ob01_tiporesp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob01_regular"]) || $this->ob01_regular != "")
           $resac = db_query("insert into db_acount values($acount,946,5914,'".AddSlashes(pg_result($resaco,$conresaco,'ob01_regular'))."','$this->ob01_regular',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob01_dtobra"]) || $this->ob01_dtobra != "")
           $resac = db_query("insert into db_acount values($acount,946,18629,'".AddSlashes(pg_result($resaco,$conresaco,'ob01_dtobra'))."','$this->ob01_dtobra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob01_processo"]) || $this->ob01_processo != "")
           $resac = db_query("insert into db_acount values($acount,946,18630,'".AddSlashes(pg_result($resaco,$conresaco,'ob01_processo'))."','$this->ob01_processo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob01_nometitularproc"]) || $this->ob01_nometitularproc != "")
           $resac = db_query("insert into db_acount values($acount,946,18631,'".AddSlashes(pg_result($resaco,$conresaco,'ob01_nometitularproc'))."','$this->ob01_nometitularproc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob01_dtprocesso"]) || $this->ob01_dtprocesso != "")
           $resac = db_query("insert into db_acount values($acount,946,18632,'".AddSlashes(pg_result($resaco,$conresaco,'ob01_dtprocesso'))."','$this->ob01_dtprocesso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob01_obs"]) || $this->ob01_obs != "")
           $resac = db_query("insert into db_acount values($acount,946,18633,'".AddSlashes(pg_result($resaco,$conresaco,'ob01_obs'))."','$this->ob01_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cadastro de obras nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ob01_codobra;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cadastro de obras nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ob01_codobra;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ob01_codobra;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ob01_codobra=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ob01_codobra));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5909,'$ob01_codobra','E')");
         $resac = db_query("insert into db_acount values($acount,946,5909,'','".AddSlashes(pg_result($resaco,$iresaco,'ob01_codobra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,946,5910,'','".AddSlashes(pg_result($resaco,$iresaco,'ob01_nomeobra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,946,5913,'','".AddSlashes(pg_result($resaco,$iresaco,'ob01_tiporesp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,946,5914,'','".AddSlashes(pg_result($resaco,$iresaco,'ob01_regular'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,946,18629,'','".AddSlashes(pg_result($resaco,$iresaco,'ob01_dtobra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,946,18630,'','".AddSlashes(pg_result($resaco,$iresaco,'ob01_processo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,946,18631,'','".AddSlashes(pg_result($resaco,$iresaco,'ob01_nometitularproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,946,18632,'','".AddSlashes(pg_result($resaco,$iresaco,'ob01_dtprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,946,18633,'','".AddSlashes(pg_result($resaco,$iresaco,'ob01_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from obras
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ob01_codobra != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ob01_codobra = $ob01_codobra ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cadastro de obras nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ob01_codobra;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cadastro de obras nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ob01_codobra;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ob01_codobra;
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
        $this->erro_sql   = "Record Vazio na Tabela:obras";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $ob01_codobra=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from obras ";
     $sql .= "      inner join obrastiporesp  on  obrastiporesp.ob02_cod = obras.ob01_tiporesp";
     $sql2 = "";
     if($dbwhere==""){
       if($ob01_codobra!=null ){
         $sql2 .= " where obras.ob01_codobra = $ob01_codobra ";
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
   function sql_query_file ( $ob01_codobra=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from obras ";
     $sql2 = "";
     if($dbwhere==""){
       if($ob01_codobra!=null ){
         $sql2 .= " where obras.ob01_codobra = $ob01_codobra ";
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

  function sql_query_infob ( $ob01_codobra=null,$campos="*",$ordem=null,$dbwhere=""){

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
    $sql .= " from obras                                                                             ";
    $sql .= "inner join obrasender       on obrasender.ob07_codobra    = obras.ob01_codobra          ";
    $sql .= "inner join ruas             on ruas.j14_codigo            = obrasender.ob07_lograd      ";
    $sql .= "inner join bairro           on bairro.j13_codi            = obrasender.ob07_bairro      ";
    $sql .= "inner join obrastiporesp    on obrastiporesp.ob02_cod     = obras.ob01_tiporesp         ";
    $sql .= "inner join obrasresp        on obrasresp.ob10_codobra     = obras.ob01_codobra          ";
    $sql .= "inner join cgm responsavel  on responsavel.z01_numcgm     = obrasresp.ob10_numcgm       ";
    $sql .= "inner join obraspropri      on obraspropri.ob03_codobra   = obras.ob01_codobra          ";
    $sql .= "inner join cgm proprietario on proprietario.z01_numcgm    = obraspropri.ob03_numcgm     ";
    $sql .= " left join obraslote        on obraslote.ob05_codobra     = obras.ob01_codobra          ";
    $sql .= " left join lote             on lote.j34_idbql             = obraslote.ob05_idbql        ";
    $sql .= " left join obraslotei       on obraslotei.ob06_codobra    = obras.ob01_codobra          ";
    $sql .= " left join obrasalvara      on obrasalvara.ob04_codobra   = obras.ob01_codobra          ";
    $sql .= " left join obrastecnicos    on obrastecnicos.ob20_codobra = obras.ob01_codobra          ";
    $sql .= " left join obrastec         on obrastec.ob15_sequencial   = obrastecnicos.ob20_obrastec ";
    $sql .= " left join cgm tecnico      on tecnico.z01_numcgm         = obrastec.ob15_numcgm        ";

    $sql2 = "";

    if($dbwhere==""){
      if($ob01_codobra!=null ){
        $sql2 .= " where obras.ob01_codobra = $ob01_codobra ";
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
   * SQL para retornar dados para geração do
   * arquivo SISOBRANET
   *
   * @param integer $iMes  - mes da competencia
   * @param integer $iAno  - ano da competencia
   */
  function sql_queryDadosSisobra($iMes, $iAno){

  $sSqlSisobra = "select *                                                                                           \n";
  $sSqlSisobra .= "  from (                                                                                          \n";
  $sSqlSisobra .= "  select cgmResponsavel.z01_nome                                      as nomeResponsavel,         \n";
  $sSqlSisobra .= "         cgmResponsavel.z01_numcgm                                    as cgmResponsavel,          \n";
  $sSqlSisobra .= "         cgmResponsavel.z01_cgccpf                                    as cpfResponsavel,          \n";
  $sSqlSisobra .= "         cgmResponsavel.z01_ender ||', ' || cgmResponsavel.z01_numero as enderecoResponsavel,     \n";
  $sSqlSisobra .= "         cgmResponsavel.z01_bairro                                    as bairroResponsavel,       \n";
  $sSqlSisobra .= "         cgmResponsavel.z01_cep                                       as cepResponsavel,          \n";
  $sSqlSisobra .= "         cgmResponsavel.z01_uf                                        as ufResponsavel,           \n";
  $sSqlSisobra .= "         cgmResponsavel.z01_telef                                     as telefoneResponsavel,     \n";
  $sSqlSisobra .= "         cgmResponsavel.z01_fax                                       as faxResponsavel,          \n";
  $sSqlSisobra .= "         cgmResponsavel.z01_email                                     as emailResponsavel,        \n";
  $sSqlSisobra .= "         ob01_tiporesp                                                as tipoVinculoResponsavel,  \n";
  $sSqlSisobra .= "         cgmConstrutor.z01_nome                                       as nomeConstrutor,          \n";
  $sSqlSisobra .= "         cgmConstrutor.z01_numcgm                                     as cgmConstrutor,           \n";
  $sSqlSisobra .= "         cgmConstrutor.z01_cgccpf                                     as cpfConstrutor,           \n";
  $sSqlSisobra .= "         cgmConstrutor.z01_ender ||', ' || cgmConstrutor.z01_numero   as enderecoConstrutor,      \n";
  $sSqlSisobra .= "         cgmConstrutor.z01_bairro                                     as bairroConstrutor,        \n";
  $sSqlSisobra .= "         cgmConstrutor.z01_cep                                        as cepConstrutor,           \n";
  $sSqlSisobra .= "         cgmConstrutor.z01_uf                                         as ufConstrutor,            \n";
  $sSqlSisobra .= "         ob04_alvara                                                  as alvaraObra,              \n";
  $sSqlSisobra .= "         ob04_data                                                    as dataObra,                \n";
  $sSqlSisobra .= "         ob01_nomeobra                                                as nomeObra,                \n";
  $sSqlSisobra .= "         j14_nome ||', ' || ob07_numero                               as enderObra,               \n";
  $sSqlSisobra .= "         bairro.j13_descr                                             as bairroObra,              \n";
  $sSqlSisobra .= "         j29_cep                                                      as cepObra,                 \n";
  $sSqlSisobra .= "         cgmObras.z01_telef                                           as telefoneObra,            \n";
  $sSqlSisobra .= "         cgmObras.z01_fax                                             as faxObra,                 \n";
  $sSqlSisobra .= "         ob07_inicio                                                  as dataInicioObra,          \n";
  $sSqlSisobra .= "         ob07_fim                                                     as dataFimObra,             \n";
  $sSqlSisobra .= "         case                                                                                     \n";
  $sSqlSisobra .= "           when ob08_ocupacao = 10000                                                             \n";
  $sSqlSisobra .= "             then '0'                                                                             \n";
  $sSqlSisobra .= "           when ob08_ocupacao = 10001                                                             \n";
  $sSqlSisobra .= "             then '1'                                                                             \n";
  $sSqlSisobra .= "           when ob08_ocupacao = 10002                                                             \n";
  $sSqlSisobra .= "             then '2'                                                                             \n";
  $sSqlSisobra .= "           else ''                                                                                \n";
  $sSqlSisobra .= "         end                                                         as tipoOcupacaoObra,         \n";
  $sSqlSisobra .= "         case                                                                                     \n";
  $sSqlSisobra .= "           when ob08_tipoconstr = 20000                                                           \n";
  $sSqlSisobra .= "             then '0'                                                                             \n";
  $sSqlSisobra .= "           when ob08_tipoconstr = 20001                                                           \n";
  $sSqlSisobra .= "             then '1'                                                                             \n";
  $sSqlSisobra .= "           when ob08_tipoconstr = 20002                                                           \n";
  $sSqlSisobra .= "             then '2'                                                                             \n";
  $sSqlSisobra .= "           else ''                                                                                \n";
  $sSqlSisobra .= "         end                                                         as tipoConstrucaoObra,       \n";
  $sSqlSisobra .= "         ob08_area                                                   as areaObra,                 \n";
  $sSqlSisobra .= "         case when ob08_tipolanc = 30001                                                          \n";
  $sSqlSisobra .= "           then                                                                                   \n";
  $sSqlSisobra .= "             case                                                                                 \n";
  $sSqlSisobra .= "               when ob08_ocupacao = 10000                                                         \n";
  $sSqlSisobra .= "                 then '0'                                                                         \n";
  $sSqlSisobra .= "               when ob08_ocupacao = 10001                                                         \n";
  $sSqlSisobra .= "                 then '1'                                                                         \n";
  $sSqlSisobra .= "               when ob08_ocupacao = 10002                                                         \n";
  $sSqlSisobra .= "                 then '2'                                                                         \n";
  $sSqlSisobra .= "               else ''                                                                            \n";
  $sSqlSisobra .= "             end                                                                                  \n";
  $sSqlSisobra .= "             else ''                                                                              \n";
  $sSqlSisobra .= "          end                                                        as tipoOcupacaoDemolicao,    \n";
  $sSqlSisobra .= "          case when ob08_tipolanc = 30001                                                         \n";
  $sSqlSisobra .= "            then                                                                                  \n";
  $sSqlSisobra .= "              case                                                                                \n";
  $sSqlSisobra .= "                when ob08_tipoconstr = 20000                                                      \n";
  $sSqlSisobra .= "                  then '0'                                                                        \n";
  $sSqlSisobra .= "                when ob08_tipoconstr = 20001                                                      \n";
  $sSqlSisobra .= "                  then '1'                                                                        \n";
  $sSqlSisobra .= "                when ob08_tipoconstr = 20002                                                      \n";
  $sSqlSisobra .= "                  then '2'                                                                        \n";
  $sSqlSisobra .= "                else ''                                                                           \n";
  $sSqlSisobra .= "              end                                                                                 \n";
  $sSqlSisobra .= "            else ''                                                                               \n";
  $sSqlSisobra .= "          end                                                        as tipoDemolicao,            \n";
  $sSqlSisobra .= "          case                                                                                    \n";
  $sSqlSisobra .= "            when ob08_tipolanc = 30001                                                            \n";
  $sSqlSisobra .= "              then ob08_area                                                                      \n";
  $sSqlSisobra .= "              else null                                                                           \n";
  $sSqlSisobra .= "          end                                                      as areaDemolicao,              \n";
  $sSqlSisobra .= "          case when ob08_tipolanc = 30002                                                         \n";
  $sSqlSisobra .= "            then                                                                                  \n";
  $sSqlSisobra .= "              case                                                                                \n";
  $sSqlSisobra .= "                when ob08_ocupacao = 10000                                                        \n";
  $sSqlSisobra .= "                  then '0'                                                                        \n";
  $sSqlSisobra .= "                when ob08_ocupacao = 10001                                                        \n";
  $sSqlSisobra .= "                  then '1'                                                                        \n";
  $sSqlSisobra .= "                when ob08_ocupacao = 10002                                                        \n";
  $sSqlSisobra .= "                  then '2'                                                                        \n";
  $sSqlSisobra .= "                else ''                                                                           \n";
  $sSqlSisobra .= "              end                                                                                 \n";
  $sSqlSisobra .= "            else ''                                                                               \n";
  $sSqlSisobra .= "          end                                                        as tipoOcupacaoAcrescimo,    \n";
  $sSqlSisobra .= "          case when ob08_tipolanc = 30002                                                         \n";
  $sSqlSisobra .= "            then                                                                                  \n";
  $sSqlSisobra .= "              case                                                                                \n";
  $sSqlSisobra .= "                when ob08_tipoconstr = 20000                                                      \n";
  $sSqlSisobra .= "                  then '0'                                                                        \n";
  $sSqlSisobra .= "                when ob08_tipoconstr = 20001                                                      \n";
  $sSqlSisobra .= "                  then '1'                                                                        \n";
  $sSqlSisobra .= "                when ob08_tipoconstr = 20002                                                      \n";
  $sSqlSisobra .= "                  then '2'                                                                        \n";
  $sSqlSisobra .= "                else ''                                                                           \n";
  $sSqlSisobra .= "              end                                                                                 \n";
  $sSqlSisobra .= "            else ''                                                                               \n";
  $sSqlSisobra .= "          end                                                        as tipoAcrescimo,            \n";
  $sSqlSisobra .= "          case                                                                                    \n";
  $sSqlSisobra .= "            when ob08_tipolanc = 30002                                                            \n";
  $sSqlSisobra .= "              then ob08_area                                                                      \n";
  $sSqlSisobra .= "              else null                                                                           \n";
  $sSqlSisobra .= "          end                                                        as areaAcrescimo,            \n";
  $sSqlSisobra .= "          ob07_areaatual                                             as areaExistente,            \n";
  $sSqlSisobra .= "          case when ob08_tipolanc = 30003                                                         \n";
  $sSqlSisobra .= "            then                                                                                  \n";
  $sSqlSisobra .= "              case                                                                                \n";
  $sSqlSisobra .= "                when ob08_ocupacao = 10000                                                        \n";
  $sSqlSisobra .= "                  then '0'                                                                        \n";
  $sSqlSisobra .= "                when ob08_ocupacao = 10001                                                        \n";
  $sSqlSisobra .= "                  then '1'                                                                        \n";
  $sSqlSisobra .= "                when ob08_ocupacao = 10002                                                        \n";
  $sSqlSisobra .= "                  then '2'                                                                        \n";
  $sSqlSisobra .= "                else ''                                                                           \n";
  $sSqlSisobra .= "              end                                                                                 \n";
  $sSqlSisobra .= "            else ''                                                                               \n";
  $sSqlSisobra .= "          end                                                        as tipoOcupacaoReforma,      \n";
  $sSqlSisobra .= "          case when ob08_tipolanc = 30003                                                         \n";
  $sSqlSisobra .= "            then                                                                                  \n";
  $sSqlSisobra .= "              case                                                                                \n";
  $sSqlSisobra .= "                when ob08_tipoconstr = 20000                                                      \n";
  $sSqlSisobra .= "                  then '0'                                                                        \n";
  $sSqlSisobra .= "                when ob08_tipoconstr = 20001                                                      \n";
  $sSqlSisobra .= "                  then '1'                                                                        \n";
  $sSqlSisobra .= "                when ob08_tipoconstr = 20002                                                      \n";
  $sSqlSisobra .= "                  then '2'                                                                        \n";
  $sSqlSisobra .= "                else ''                                                                           \n";
  $sSqlSisobra .= "              end                                                                                 \n";
  $sSqlSisobra .= "            else ''                                                                               \n";
  $sSqlSisobra .= "          end                                                        as tipoReforma,              \n";
  $sSqlSisobra .= "          case                                                                                    \n";
  $sSqlSisobra .= "            when ob08_tipolanc = 30003                                                            \n";
  $sSqlSisobra .= "              then ob08_area                                                                      \n";
  $sSqlSisobra .= "              else null                                                                           \n";
  $sSqlSisobra .= "          end                                                        as areaReforma,              \n";
  $sSqlSisobra .= "          ob09_habite                                                as numeroHabitese,           \n";
  $sSqlSisobra .= "          ob09_data                                                  as dataHabitese,             \n";
  $sSqlSisobra .= "          ob09_area                                                  as areaHabitese,             \n";
  $sSqlSisobra .= "          case                                                                                    \n";
  $sSqlSisobra .= "            when ob09_parcial is true                                                             \n";
  $sSqlSisobra .= "              then 'P'                                                                            \n";
  $sSqlSisobra .= "            else   'T'                                                                            \n";
  $sSqlSisobra .= "          end                                                       as tipoHabitese,              \n";
  $sSqlSisobra .= "          ob07_unidades                                             as iUnidades,                 \n";
  $sSqlSisobra .= "          ob07_pavimentos                                           as iPavimentos,               \n";
  $sSqlSisobra .= "          ob01_codobra                                              as codigoObra,                \n";
  $sSqlSisobra .= "          ob09_codhab                                               as codigoHabitese             \n";
  $sSqlSisobra .= "           from obras                                                                             \n";
  $sSqlSisobra .= "            left join obrasender    on ob01_codobra   = ob07_codobra                              \n";
  $sSqlSisobra .= "            left join ruas          on ob07_lograd    = j14_codigo                                \n";
  $sSqlSisobra .= "            left join obrastiporesp on ob01_codobra   = ob02_cod                                  \n";
  $sSqlSisobra .= "            left join obrasconstr   on ob01_codobra   = ob08_codobra                              \n";
  $sSqlSisobra .= "            left join obraspropri   on ob01_codobra   = ob03_codobra                              \n";
  $sSqlSisobra .= "            left join obrasiptubase on ob24_obras     = ob01_codobra                              \n";
  $sSqlSisobra .= "            left join iptubase      on j01_matric     = ob24_iptubase                             \n";
  $sSqlSisobra .= "            left join lote          on j34_idbql      = j01_idbql                                 \n";
  $sSqlSisobra .= "            left join bairro        on j13_codi       = ob07_bairro                               \n";
  $sSqlSisobra .= "            left join obraslotei    on ob01_codobra   = ob06_codobra                              \n";
  $sSqlSisobra .= "            left join obrashabite   on ob08_codconstr = ob09_codconstr                            \n";
  $sSqlSisobra .= "            left join cgm        as cgmResponsavel       on ob03_numcgm                     = cgmResponsavel.z01_numcgm             \n";
  $sSqlSisobra .= "            left join obrasresp  as obrasrespConstrutor  on ob01_codobra                    = obrasrespConstrutor.ob10_codobra      \n";
  $sSqlSisobra .= "            left join cgm        as cgmConstrutor        on obrasrespConstrutor.ob10_numcgm = cgmConstrutor.z01_numcgm              \n";
  $sSqlSisobra .= "            left join obrasalvara                        on ob01_codobra                    = ob04_codobra                          \n";
  $sSqlSisobra .= "            left join ruascep                            on j29_codigo                      = j14_codigo                            \n";
  $sSqlSisobra .= "            left join cgm        as cgmObras             on cgmObras.z01_numcgm             = ob03_numcgm                           \n";
  $sSqlSisobra .= "           where extract(month from ob04_data) = {$iMes}                                                                            \n";
  $sSqlSisobra .= "             and extract(year from ob04_data)  = {$iAno}                                                                            \n";
  $sSqlSisobra .= "             and ob04_codobra is not null                                                                                           \n";
  $sSqlSisobra .= "             and ob01_codobra in (select ob01_codobra                                                                               \n";
  $sSqlSisobra .= "                                     from obras                                                                                     \n";
  $sSqlSisobra .= "                                          left join obrasenvioreg    on ob01_codobra         = ob17_codobra                         \n";
  $sSqlSisobra .= "                                          left join obrasenvio       on ob16_codobrasenvio   = ob17_codobrasenvio                   \n";
  $sSqlSisobra .= "                                          left join obrasenvioreghab on ob18_codobraenvioreg = ob17_codobrasenvioreg                \n";
  $sSqlSisobra .= "                                          left join obrashabite      on ob09_codhab          = ob18_codhabite                       \n";
  $sSqlSisobra .= "                                    where case when ob09_parcial is null                                                            \n";
  $sSqlSisobra .= "                                               then case when ob16_dtini is null                                                    \n";
  $sSqlSisobra .= "                                                         then true                                                                  \n";
  $sSqlSisobra .= "                                                         else (     extract( month from ob16_dtini) = {$iMes}                       \n";
  $sSqlSisobra .= "                                                                and extract( year  from ob16_dtini) = {$iAno}                       \n";
  $sSqlSisobra .= "                                                              )                                                                     \n";
  $sSqlSisobra .= "                                                    end                                                                             \n";
  $sSqlSisobra .= "                                               else ob09_parcial is true                                                            \n";
  $sSqlSisobra .= "                                          end                                                                                       \n";
  $sSqlSisobra .= "                                  )                                                                                                 \n";
  $sSqlSisobra .= "          ) as sql_base                                                                                                             \n";
  $sSqlSisobra .= "    where case when sql_base.numerohabitese is null                                                                                 \n";
  $sSqlSisobra .= "               then sql_base.codigoobra not in (select ob17_codobra                                                                 \n";
  $sSqlSisobra .= "                                                 from obrasenvioreg                                                                 \n";
  $sSqlSisobra .= "                                                      left join obrasenvioreghab on ob18_codobraenvioreg = ob17_codobrasenvioreg    \n";
  $sSqlSisobra .= "                                                      left join obrasenvio       on ob16_codobrasenvio   = ob17_codobrasenvio       \n";
  $sSqlSisobra .= "                                                where extract( month from ob16_dtini) = {$iMes}                                     \n";
  $sSqlSisobra .= "                                                  and extract( year  from ob16_dtini) = {$iAno}                                     \n";
  $sSqlSisobra .= "                                                  and ob18_codhabite is null                                                        \n";
  $sSqlSisobra .= "                                              )                                                                                     \n";
  $sSqlSisobra .= "               else cast(sql_base.numerohabitese as integer) not in (                                                                                \n";
  $sSqlSisobra .= "                                                   select ob18_codhabite                                                            \n";
  $sSqlSisobra .= "                                                     from obrasenvioreghab                                                          \n";
  $sSqlSisobra .= "                                                          inner join obrasenvioreg on ob18_codobraenvioreg = ob17_codobrasenvioreg  \n";
  $sSqlSisobra .= "                                                          inner join obrasenvio    on ob16_codobrasenvio   = ob17_codobrasenvio     \n";
  $sSqlSisobra .= "                                                    where extract( month from ob16_dtini) = {$iMes}                                 \n";
  $sSqlSisobra .= "                                                      and extract( year  from ob16_dtini) = {$iAno}                                 \n";
  $sSqlSisobra .= "                                                  )                                                                                 \n";
  $sSqlSisobra .= "                                                                                                                                    \n";
  $sSqlSisobra .= "                                                                                                                                    \n";
  $sSqlSisobra .= "          end                                                                                                                       \n";


  return $sSqlSisobra;

  }
   function sql_query_obras_construcoes($iCodigoObra) {

    $sSql  = "select ob08_codconstr,                                                                \n";
    $sSql .= "       ob08_codobra,                                                                  \n";
    $sSql .= "       ob01_nomeobra,                                                                 \n";
    $sSql .= "       ob08_area,                                                                     \n";
    $sSql .= "       ob08_ocupacao,                                                                 \n";
    $sSql .= "       a.j31_descr as ob08_descrocupacao,                                             \n";
    $sSql .= "       ob08_tipoconstr,                                                               \n";
    $sSql .= "       b.j31_descr as ob08_descrtipoconstr,                                           \n";
    $sSql .= "       ob08_tipolanc,                                                                 \n";
    $sSql .= "       c.j31_descr as ob08_descrtipolanc,                                             \n";
    $sSql .= "       ob07_lograd,                                                                   \n";
    $sSql .= "       j14_nome,                                                                      \n";
    $sSql .= "       ob07_numero,                                                                   \n";
    $sSql .= "       ob07_compl,                                                                    \n";
    $sSql .= "       ob07_bairro,                                                                   \n";
    $sSql .= "       j13_descr,                                                                     \n";
    $sSql .= "       ob07_areaatual,                                                                \n";
    $sSql .= "       ob07_unidades,                                                                 \n";
    $sSql .= "       ob07_pavimentos,                                                               \n";
    $sSql .= "       ob07_inicio,                                                                   \n";
    $sSql .= "       ob07_fim                                                                       \n";
    $sSql .= "  from obras                                                                          \n";
    $sSql .= " inner join obrasconstr   on obrasconstr.ob08_codobra   = obras.ob01_codobra          \n";
    $sSql .= " inner join obrasender    on obrasender.ob07_codconstr  = obrasconstr.ob08_codconstr  \n";
    $sSql .= " inner join caracter a    on a.j31_codigo               = obrasconstr.ob08_ocupacao   \n";
    $sSql .= " inner join caracter b    on b.j31_codigo               = obrasconstr.ob08_tipoconstr \n";
    $sSql .= " inner join caracter c    on c.j31_codigo               = obrasconstr.ob08_tipolanc   \n";
    $sSql .= " inner join bairro        on bairro.j13_codi            = obrasender.ob07_bairro      \n";
    $sSql .= "  left join ruas          on ruas.j14_codigo            = obrasender.ob07_lograd      \n";
    $sSql .= " where obras.ob01_codobra = {$iCodigoObra}                                            \n";
    return $sSql;

  }
   /**
   * Busca dados das obras incluindo dados da matricula
   * @param  integer $iCodigoObra
   * @param  string  $sCampos
   * @param  string  $sOrdem
   * @param  strign  $sWhere
   * @return string
   */
  function sql_query_consultaObras ( $iCodigoObra = null, $sCampos = "*", $sOrdem = null, $sWhere = "" ) {

    $sql = " select                                                                                     ";

    if( $sCampos != "*" ){

      $campos_sql = explode("#",$sCampos);
      $sql       .= implode(", ", $campos_sql);
    } else {
      $sql       .= $sCampos;
    }

    $sql .= " from obras                                                                                ";
    $sql .= "      inner join obrastiporesp  on obrastiporesp.ob02_cod      = obras.ob01_tiporesp       ";
    $sql .= "      inner join obrasresp      on obrasresp.ob10_codobra      = obras.ob01_codobra        ";
    $sql .= "      inner join cgm as      r  on obrasresp.ob10_numcgm       = r.z01_numcgm              ";
    $sql .= "      inner join obraspropri    on obras.ob01_codobra          = obraspropri.ob03_codobra  ";
    $sql .= "      inner join cgm as      p  on obraspropri.ob03_numcgm     = p.z01_numcgm              ";
    $sql .= "      left  join obrasiptubase  on obras.ob01_codobra          = obrasiptubase.ob24_obras  ";
    $sql .= "      left  join obraslotei     on obras.ob01_codobra          = obraslotei.ob06_codobra   ";
    $sql .= "      left  join iptubase       on obrasiptubase.ob24_iptubase = iptubase.j01_matric       ";
    $sql .= "      left  join obrasalvara    on obras.ob01_codobra          = obrasalvara.ob04_codobra  ";
    $sql .= "      left  join obrasconstr    on obrasconstr.ob08_codobra    = obras.ob01_codobra        ";
    $sql .= "      left  join obrasender     on obrasender.ob07_codconstr   = obrasconstr.ob08_codconstr";
    $sql .= "      left  join ruas           on obrasender.ob07_lograd      = ruas.j14_codigo           ";
    $sql .= "      left  join bairro         on obrasender.ob07_bairro      = bairro.j13_codi           ";

    $sql2 = "";

    if ( !empty($sWhere) ) {
      $sql2 = " where $sWhere";
    }
    $sql .= $sql2;

    if ( !empty($sOrdem) ) {

      $sql       .= " order by ";
      $campos_sql = explode("#",$sOrdem);
      $sql       .= implode(", ", $campos_sql);
    }
    return $sql;
  }
}
?>