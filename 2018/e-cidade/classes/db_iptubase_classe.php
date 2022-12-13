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

//MODULO: cadastro
//CLASSE DA ENTIDADE iptubase
class cl_iptubase {
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
   var $j01_matric = 0;
   var $j01_numcgm = 0;
   var $j01_idbql = 0;
   var $j01_baixa_dia = null;
   var $j01_baixa_mes = null;
   var $j01_baixa_ano = null;
   var $j01_baixa = null;
   var $j01_codave = 0;
   var $j01_fracao = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 j01_matric = int4 = Matrícula do Imóvel
                 j01_numcgm = int4 = Numcgm
                 j01_idbql = int4 = Id Lote
                 j01_baixa = date = Baixa
                 j01_codave = int4 = Codigo da Averbacao
                 j01_fracao = float8 = Fracao Ideal
                 ";
   //funcao construtor da classe
   function cl_iptubase() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("iptubase");
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
       $this->j01_matric = ($this->j01_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j01_matric"]:$this->j01_matric);
       $this->j01_numcgm = ($this->j01_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["j01_numcgm"]:$this->j01_numcgm);
       $this->j01_idbql = ($this->j01_idbql == ""?@$GLOBALS["HTTP_POST_VARS"]["j01_idbql"]:$this->j01_idbql);
       if($this->j01_baixa == ""){
         $this->j01_baixa_dia = ($this->j01_baixa_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["j01_baixa_dia"]:$this->j01_baixa_dia);
         $this->j01_baixa_mes = ($this->j01_baixa_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["j01_baixa_mes"]:$this->j01_baixa_mes);
         $this->j01_baixa_ano = ($this->j01_baixa_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["j01_baixa_ano"]:$this->j01_baixa_ano);
         if($this->j01_baixa_dia != ""){
            $this->j01_baixa = $this->j01_baixa_ano."-".$this->j01_baixa_mes."-".$this->j01_baixa_dia;
         }
       }
       $this->j01_codave = ($this->j01_codave == ""?@$GLOBALS["HTTP_POST_VARS"]["j01_codave"]:$this->j01_codave);
       $this->j01_fracao = ($this->j01_fracao == ""?@$GLOBALS["HTTP_POST_VARS"]["j01_fracao"]:$this->j01_fracao);
     }else{
       $this->j01_matric = ($this->j01_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j01_matric"]:$this->j01_matric);
     }
   }
   // funcao para inclusao
   function incluir ($j01_matric){
      $this->atualizacampos();
     if($this->j01_numcgm == null ){
       $this->erro_sql = " Campo Numcgm nao Informado.";
       $this->erro_campo = "j01_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j01_idbql == null ){
       $this->erro_sql = " Campo Id Lote nao Informado.";
       $this->erro_campo = "j01_idbql";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j01_baixa == null ){
       $this->j01_baixa = "null";
     }
     if($this->j01_codave == null ){
       $this->j01_codave = "0";
     }
     if($this->j01_fracao == null ){
       $this->j01_fracao = "0";
     }
     if($j01_matric == "" || $j01_matric == null ){
       $result = db_query("select nextval('iptubase_j01_matric_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: iptubase_j01_matric_seq do campo: j01_matric";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->j01_matric = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from iptubase_j01_matric_seq");
       if(($result != false) && (pg_result($result,0,0) < $j01_matric)){
         $this->erro_sql = " Campo j01_matric maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j01_matric = $j01_matric;
       }
     }
     if(($this->j01_matric == null) || ($this->j01_matric == "") ){
       $this->erro_sql = " Campo j01_matric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into iptubase(
                                       j01_matric
                                      ,j01_numcgm
                                      ,j01_idbql
                                      ,j01_baixa
                                      ,j01_codave
                                      ,j01_fracao
                       )
                values (
                                $this->j01_matric
                               ,$this->j01_numcgm
                               ,$this->j01_idbql
                               ,".($this->j01_baixa == "null" || $this->j01_baixa == ""?"null":"'".$this->j01_baixa."'")."
                               ,$this->j01_codave
                               ,$this->j01_fracao
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Proprietario do Lote ($this->j01_matric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Proprietario do Lote já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Proprietario do Lote ($this->j01_matric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j01_matric;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j01_matric));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,141,'$this->j01_matric','I')");
       $resac = db_query("insert into db_acount values($acount,27,141,'','".AddSlashes(pg_result($resaco,0,'j01_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,27,142,'','".AddSlashes(pg_result($resaco,0,'j01_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,27,143,'','".AddSlashes(pg_result($resaco,0,'j01_idbql'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,27,144,'','".AddSlashes(pg_result($resaco,0,'j01_baixa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,27,145,'','".AddSlashes(pg_result($resaco,0,'j01_codave'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,27,368,'','".AddSlashes(pg_result($resaco,0,'j01_fracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($j01_matric=null) {
      $this->atualizacampos();
     $sql = " update iptubase set ";
     $virgula = "";
     if(trim($this->j01_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j01_matric"])){
       $sql  .= $virgula." j01_matric = $this->j01_matric ";
       $virgula = ",";
       if(trim($this->j01_matric) == null ){
         $this->erro_sql = " Campo Matrícula do Imóvel nao Informado.";
         $this->erro_campo = "j01_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j01_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j01_numcgm"])){
       $sql  .= $virgula." j01_numcgm = $this->j01_numcgm ";
       $virgula = ",";
       if(trim($this->j01_numcgm) == null ){
         $this->erro_sql = " Campo Numcgm nao Informado.";
         $this->erro_campo = "j01_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j01_idbql)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j01_idbql"])){
       $sql  .= $virgula." j01_idbql = $this->j01_idbql ";
       $virgula = ",";
       if(trim($this->j01_idbql) == null ){
         $this->erro_sql = " Campo Id Lote nao Informado.";
         $this->erro_campo = "j01_idbql";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j01_baixa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j01_baixa_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["j01_baixa_dia"] !="") ){
       $sql  .= $virgula." j01_baixa = '$this->j01_baixa' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["j01_baixa_dia"])){
         $sql  .= $virgula." j01_baixa = null ";
         $virgula = ",";
       }
     }
     if(trim($this->j01_codave)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j01_codave"])){
        if(trim($this->j01_codave)=="" && isset($GLOBALS["HTTP_POST_VARS"]["j01_codave"])){
           $this->j01_codave = "0" ;
        }
       $sql  .= $virgula." j01_codave = $this->j01_codave ";
       $virgula = ",";
     }
     if(trim($this->j01_fracao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j01_fracao"])){
        if(trim($this->j01_fracao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["j01_fracao"])){
           $this->j01_fracao = "0" ;
        }
       $sql  .= $virgula." j01_fracao = $this->j01_fracao ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($j01_matric!=null){
       $sql .= " j01_matric = $this->j01_matric";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j01_matric));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,141,'$this->j01_matric','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j01_matric"]) || isset($this->j01_matric))
           $resac = db_query("insert into db_acount values($acount,27,141,'".AddSlashes(pg_result($resaco,$conresaco,'j01_matric'))."','$this->j01_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j01_numcgm"]) || isset($this->j01_numcgm))
           $resac = db_query("insert into db_acount values($acount,27,142,'".AddSlashes(pg_result($resaco,$conresaco,'j01_numcgm'))."','$this->j01_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j01_idbql"]) || isset($this->j01_idbql))
           $resac = db_query("insert into db_acount values($acount,27,143,'".AddSlashes(pg_result($resaco,$conresaco,'j01_idbql'))."','$this->j01_idbql',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j01_baixa"]) || isset($this->j01_baixa))
           $resac = db_query("insert into db_acount values($acount,27,144,'".AddSlashes(pg_result($resaco,$conresaco,'j01_baixa'))."','$this->j01_baixa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j01_codave"]) || isset($this->j01_codave))
           $resac = db_query("insert into db_acount values($acount,27,145,'".AddSlashes(pg_result($resaco,$conresaco,'j01_codave'))."','$this->j01_codave',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j01_fracao"]) || isset($this->j01_fracao))
           $resac = db_query("insert into db_acount values($acount,27,368,'".AddSlashes(pg_result($resaco,$conresaco,'j01_fracao'))."','$this->j01_fracao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Proprietario do Lote nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j01_matric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Proprietario do Lote nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j01_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j01_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($j01_matric=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j01_matric));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,141,'$j01_matric','E')");
         $resac = db_query("insert into db_acount values($acount,27,141,'','".AddSlashes(pg_result($resaco,$iresaco,'j01_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,27,142,'','".AddSlashes(pg_result($resaco,$iresaco,'j01_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,27,143,'','".AddSlashes(pg_result($resaco,$iresaco,'j01_idbql'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,27,144,'','".AddSlashes(pg_result($resaco,$iresaco,'j01_baixa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,27,145,'','".AddSlashes(pg_result($resaco,$iresaco,'j01_codave'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,27,368,'','".AddSlashes(pg_result($resaco,$iresaco,'j01_fracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from iptubase
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j01_matric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j01_matric = $j01_matric ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Proprietario do Lote nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j01_matric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Proprietario do Lote nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j01_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j01_matric;
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
        $this->erro_sql   = "Record Vazio na Tabela:iptubase";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function proprietario_query ( $j01_matric=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from proprietario";
     $sql2 = "";
     if($dbwhere==""){
       if($j01_matric!=null ){
         $sql2 .= " where proprietario.j01_matric = $j01_matric ";
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
   function proprietario_record($sql) {
     $result = @db_query($sql);
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
        $this->erro_sql   = "Proprietarios nao Encontrados";
        $this->erro_msg   = "Usuário: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sqlmatriculas_bairros($pesquisaBairro=0){
  $sql = "
  select iptubase.j01_matric, cgm.z01_nome,cgm.z01_ender,cgm.z01_munic,cgm.z01_cep,cgm.z01_uf ,lote.*
          from lote
          inner join iptubase on j34_idbql = j01_idbql
          inner join cgm on z01_numcgm = j01_numcgm
   ";
  if($pesquisaBairro!=0){
       $sql .= "where j34_bairro = $pesquisaBairro";
   }
   return $sql;
}
   function sqlmatriculas_IDBQL($pesquisaPorIDBQL=0){
   $sql = "
    select distinct * from (  select j01_matric, 'PROPRIETARIO'::varchar(12) as proprietario, j01_idbql, cgm.z01_nome
                                      from iptubase
                                      inner join cgm on j01_numcgm = z01_numcgm
                                      where j01_idbql = $pesquisaPorIDBQL
     ) as dados
     inner join lote on j34_idbql = j01_idbql
     left outer join testpri on j49_idbql = j01_idbql
     left outer join ruas on j49_codigo = j14_codigo
     left outer join bairro on j34_bairro = j13_codi
   ";
   return $sql;
}
   function sqlmatriculas_imobiliaria($pesquisaPorImobiliaria=0){
   $sql = "
    select distinct * from (  select j01_matric, c.z01_nome as proprietario, j01_idbql, cgm.z01_nome
                                   from imobil
 	                 inner join iptubase on j44_matric = j01_matric
                                      inner join cgm on j01_numcgm = cgm.z01_numcgm
	                 inner join cgm c on j44_numcgm = c.z01_numcgm
                                      where j44_numcgm = $pesquisaPorImobiliaria
	) as dados
	  inner join lote on j34_idbql = j01_idbql
	  left outer join testpri on j49_idbql = j01_idbql
	  left outer join ruas on j49_codigo = j14_codigo
	  left outer join bairro on j34_bairro = j13_codi
    ";
    return $sql;
   }
   function sqlmatriculas_nome($pesquisaPorNome=0,$sCampos="*"){
$sql = "
   select distinct {$sCampos} from ( select j01_matric, 'PROPRIETARIO'::varchar(12) as proprietario, j01_idbql, cgm.z01_nome, j01_baixa,

																	   case
																	     when j01_matric is null and j18_testadanumero = true
																	       then testadanumero.j15_numero
																	     else iptuconstr.j39_numero
																	   end as numero,

                                     case
                                       when j01_matric is null and j18_testadanumero = true
                                         then testadanumero.j15_compl
                                       else iptuconstr.j39_compl
                                     end as complemento

                                     from iptubase
                                     inner join cgm            on j01_numcgm = z01_numcgm
                                     inner join cfiptu         on j18_anousu = ".db_getsession('DB_anousu')."
																		 left join iptuconstr      on j39_matric = j01_matric
																		                          and j39_dtdemo is null
																		                          and j39_idprinc is true
																		 left join testadanumero   on testadanumero.j15_idbql = j01_idbql

                                     where j01_numcgm = $pesquisaPorNome
   union
                                      select j01_matric, 'OUTRO PROPR'::varchar(12) as proprietario, j01_idbql, cgm.z01_nome, j01_baixa,

                                      case
                                        when j01_matric is null and j18_testadanumero = true
                                          then testadanumero.j15_numero
                                        else iptuconstr.j39_numero
                                      end as numero,

                                      case
                                        when j01_matric is null and j18_testadanumero = true
                                          then testadanumero.j15_compl
                                        else iptuconstr.j39_compl
                                      end as complemento

                                      from propri
                                      inner join iptubase      on j42_matric = j01_matric
                                      inner join cgm           on j42_numcgm = z01_numcgm
                                      inner join cfiptu        on j18_anousu = ".db_getsession('DB_anousu')."
																		  left join iptuconstr     on j39_matric = j01_matric
																		                          and j39_dtdemo is null
																		                          and j39_idprinc is true
																		  left join testadanumero  on testadanumero.j15_idbql = j01_idbql

                                      where j42_numcgm = $pesquisaPorNome
   union
                                      select j01_matric, 'PROMITENTE'::varchar(12) as proprietario, j01_idbql, cgm.z01_nome, j01_baixa,

                                      case
                                        when j01_matric is null and j18_testadanumero = true
                                          then testadanumero.j15_numero
                                        else iptuconstr.j39_numero
                                      end as numero,

                                      case
                                        when j01_matric is null and j18_testadanumero = true
                                          then testadanumero.j15_compl
                                        else iptuconstr.j39_compl
                                      end as complemento

                                      from promitente
                                      inner join iptubase      on j41_matric = j01_matric
                                      inner join cgm           on j41_numcgm = z01_numcgm
                                      inner join cfiptu        on j18_anousu = ".db_getsession('DB_anousu')."
																	    left join iptuconstr     on j39_matric = j01_matric
																	                            and j39_dtdemo is null
																	                            and j39_idprinc is true
																	    left join testadanumero  on testadanumero.j15_idbql = j01_idbql


                                      where j41_numcgm = $pesquisaPorNome
	) as dados


	  inner join lote          on j34_idbql  = j01_idbql
	  left outer join testpri  on j49_idbql  = j01_idbql
    left outer join ruas     on j49_codigo = j14_codigo
    left outer join ruastipo on j88_codigo = j14_tipo
	  left outer join bairro   on j34_bairro = j13_codi

    ";
    return $sql;
   }
   function sqlmatriculas_nome_numero($pesquisaPorNome=0, $regraCgmIptu=2){

	  switch($regraCgmIptu) {
			case 0:
				$sql = "
					select distinct *
					  from (
						 select distinct * from (
									select distinct
												 j01_matric,
												 case when j39_matric is null then 'TERRITORIAL' else 'PREDIAL' end as j01_tipoimp,
												 'PROPRIETARIO'::varchar(12) as proprietario,
												 j01_idbql,
												 cgm.z01_nome,
												 j01_baixa
									from   iptubase
									left join iptuconstr on j39_matric = j01_matric and j39_dtdemo is null
									inner join cgm on j01_numcgm = z01_numcgm
									where  j01_numcgm = $pesquisaPorNome
									union
									select j01_matric,
												 case when j39_matric is null then 'TERRITORIAL' else 'PREDIAL' end as j01_tipoimp,
												 'OUTRO PROPR'::varchar(12) as proprietario,
												 j01_idbql,
												 cgm.z01_nome,
												 j01_baixa
									from   propri
									inner join iptubase on j42_matric = j01_matric
									left join iptuconstr on j39_matric = j01_matric and j39_dtdemo is null
									inner join cgm on j42_numcgm = z01_numcgm
									where  j42_numcgm = $pesquisaPorNome
									union
									select j01_matric,
												 case when j39_matric is null then 'TERRITORIAL' else 'PREDIAL' end as j01_tipoimp,
												 'PROMITENTE'::varchar(12) as proprietario,
												 j01_idbql,
												 cgm.z01_nome,
												 j01_baixa
									from   promitente
									inner join iptubase on j41_matric = j01_matric
									left join iptuconstr on j39_matric = j01_matric and j39_dtdemo is null
									inner join cgm on j41_numcgm = z01_numcgm
									where j41_numcgm = $pesquisaPorNome
						 ) as dados
						 inner join lote on j34_idbql = j01_idbql
						 left outer join testpri on j49_idbql = j01_idbql
						 left outer join ruas on j49_codigo = j14_codigo
						 left outer join bairro on j34_bairro = j13_codi) as x
						 inner join proprietario_ender on x.j01_matric = proprietario_ender.j01_matric
						";
				break;
			case 1:
			  $sql = "
						select distinct * from (
						 select distinct * from (
									select distinct
												 j01_matric,
												 case when j39_matric is null then 'TERRITORIAL' else 'PREDIAL' end as j01_tipoimp,
												 'PROPRIETARIO'::varchar(12) as proprietario,
												 j01_idbql,
												 cgm.z01_nome,
												 j01_baixa
									from   iptubase
									left join iptuconstr on j39_matric = j01_matric and j39_dtdemo is null
									inner join cgm on j01_numcgm = z01_numcgm
									where  j01_numcgm = $pesquisaPorNome
									union
									select j01_matric,
												 case when j39_matric is null then 'TERRITORIAL' else 'PREDIAL' end as j01_tipoimp,
												 'OUTRO PROPR'::varchar(12) as proprietario,
												 j01_idbql,
												 cgm.z01_nome,
												 j01_baixa
									from   propri
									inner join iptubase on j42_matric = j01_matric
									left join iptuconstr on j39_matric = j01_matric and j39_dtdemo is null
									inner join cgm on j42_numcgm = z01_numcgm
									where  j42_numcgm = $pesquisaPorNome
						 ) as dados
						 inner join lote on j34_idbql = j01_idbql
						 left outer join testpri on j49_idbql = j01_idbql
						 left outer join ruas on j49_codigo = j14_codigo
						 left outer join bairro on j34_bairro = j13_codi) as x
						 inner join proprietario_ender on x.j01_matric = proprietario_ender.j01_matric
				";
			  break;
			case 2: $sql = "
			 	  select distinct * from (
						 select distinct * from (
									select distinct
												 iptubase.j01_matric,
												 case when j39_matric is null then 'TERRITORIAL'
												      else 'PREDIAL' end as j01_tipoimp,
												 case when j41_matric is null then 'PROPRIETARIO'::varchar(12)
												      else 'PROMITENTE'::varchar(12) end as proprietario,
												 j01_idbql,
												 case when j41_matric is null then a.z01_nome
												      else b.z01_nome end as z01_nome,
												 j01_baixa
									from   iptubase
									left join iptuconstr on j39_matric = iptubase.j01_matric and j39_dtdemo is null
									left join promitente on j41_matric = iptubase.j01_matric and j41_tipopro is true
									left join cgm a on a.z01_numcgm = iptubase.j01_numcgm
									left join cgm b on b.z01_numcgm = j41_numcgm
									where case when j41_matric is null then iptubase.j01_numcgm = $pesquisaPorNome
									           else j41_numcgm = $pesquisaPorNome end
						 ) as dados
						 inner join lote on j34_idbql = dados.j01_idbql
						 left outer join testpri on j49_idbql = dados.j01_idbql
						 left outer join ruas on j49_codigo = j14_codigo
						 left outer join bairro on j34_bairro = j13_codi) as x
						 inner join proprietario_ender on x.j01_matric = proprietario_ender.j01_matric


			";
			  break;
		 }
		 //die($sql);
    return $sql;
   }
   function sqlmatriculas_ruas($pesquisaRua=0,$numero=0,$filtrotipo='todos'){
     $order_by = "";
     $sql = " select distinct j01_matric,j01_tipoimp,z01_nome,j40_refant,j39_numero,j39_compl,proprietario
              from proprietario";
     if($pesquisaRua!=0){
       $sql .= " where (j14_codigo = $pesquisaRua or codpri = $pesquisaRua)";
       if($numero!=0){
          $sql .= "  and  j39_numero >= $numero";
          $order_by = "order by  j39_numero,j01_matric";
       }
     }
	 if($filtrotipo!='todos'){
	    $sql .= " and j01_tipoimp = '".$filtrotipo."'";
	 }
     if ($order_by!=""){
        $sql .= " $order_by  ";
     }else{
              $sql .= " order by j01_matric, j39_numero";
     }
     return $sql;
   }
   function sqlmatriculas_setor($pesquisasetor=0){
    $sql = "select lote.j34_idbql as db_lote, j34_setor, j34_quadra, j34_lote
	              , j34_area, j34_areal,j01_matric,
	              ruas.j14_nome, bairro.j13_descr
                  from lote
				  inner join bairro on j13_codi = j34_bairro
				  inner join iptubase on j01_idbql = j34_idbql
                  left outer join testpri on j34_idbql = j49_idbql
				  left outer join ruas on j14_codigo = j49_codigo";
    if($pesquisasetor!=0){
       $sql .= " where j34_setor = $pesquisasetor";
     }
    return $sql;
}
   function sqlmatriculas_setorQuadra($pesquisasetor="",$pesquisaquadra=""){
    $sql = "select lote.j34_idbql as db_lote, j34_setor, j34_quadra, j34_lote
	              , j34_area, j34_areal,j01_matric,
	              ruas.j14_nome, bairro.j13_descr
                  from lote
				  inner join bairro on j13_codi = j34_bairro
				  inner join iptubase on j01_idbql = j34_idbql
				  left outer join testpri on j34_idbql = j49_idbql
				  left outer join ruas on j14_codigo = j49_codigo";
	 if($pesquisasetor != ""){
       $sql .= " where j34_setor = '".strtoupper($pesquisasetor)."' and j34_quadra = '".strtoupper($pesquisaquadra)."'";
     }
	 return $sql;
   }
   function sql_query ( $j01_matric=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from iptubase ";
     $sql .= "      inner join lote  on  lote.j34_idbql = iptubase.j01_idbql";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = iptubase.j01_numcgm";
     $sql .= "      inner join bairro  on  bairro.j13_codi = lote.j34_bairro";
     $sql .= "      inner join setor  on  setor.j30_codi = lote.j34_setor";
     $sql2 = "";
     if($dbwhere==""){
       if($j01_matric!=null ){
         $sql2 .= " where iptubase.j01_matric = $j01_matric ";
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
   function sql_query_constr ( $j01_matric=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from iptubase ";
     $sql .= "      inner join lote  on  lote.j34_idbql = iptubase.j01_idbql";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = iptubase.j01_numcgm";
     $sql .= "      inner join bairro  on  bairro.j13_codi = lote.j34_bairro";
     $sql .= "      inner join setor  on  setor.j30_codi = lote.j34_setor";
     $sql .= "      left outer join iptuconstr on iptubase.j01_matric = iptuconstr.j39_matric";
     $sql .= "      left outer join iptuant on iptubase.j01_matric = iptuant.j40_matric";
     $sql2 = "";
     if($dbwhere==""){
       if($j01_matric!=null ){
         $sql2 .= " where iptubase.j01_matric = $j01_matric ";
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
   function sql_query_file ( $j01_matric=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from iptubase ";
     $sql2 = "";
     if($dbwhere==""){
       if($j01_matric!=null ){
         $sql2 .= " where iptubase.j01_matric = $j01_matric ";
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
   function __toString() {
     return "Object";
   }

	function sql_query_regmovel ( $j01_matric=null,$campos="*",$ordem=null,$dbwhere=""){

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

    $sql .= "  from iptubase 												  	  		  									  ";
		$sql .= " inner join lote 		 	       on j34_idbql  		   = j01_idbql  		    ";
	  $sql .= "	inner join cgm 			 	       on z01_numcgm 		   = j01_numcgm 		    ";
	  $sql .= "  left join testpri 		       on j49_idbql  		   = j01_idbql  		    ";
	  $sql .= "  left join testada 		       on j36_idbql  		   = j49_idbql  		    ";
	  $sql .= "           					        and j36_face  		   = j49_face   		    ";
	  $sql .= "      						 	          and j36_codigo 		   = j49_codigo 		    ";
	  $sql .= "  left join testadanumero     on j15_idbql        = j36_idbql          ";
	  $sql .= "                             and j15_face         = j36_face           ";
	  $sql .= "  left join ruas 		 	       on j14_codigo 		   = j49_codigo 		    ";
	  $sql .= "	 left join iptuconstr 	 	   on j01_matric 		   = j39_matric 		    ";
	  $sql .= "	 left join iptuant 		       on j01_matric 		   = j40_matric 		    ";
	  $sql .= "  left join ruas as ruase     on ruase.j14_codigo = j39_codigo 		    ";
	  $sql .= "  left join iptubaseregimovel on j04_matric	 	   = j01_matric 		    ";
	  $sql .= "  left join setorregimovel    on j69_sequencial   = j04_setorregimovel ";
	  $sql .= "  left join loteloc           on j06_idbql        = j01_idbql					";
	 	$sql .= "	 left join setorloc          on j05_codigo       = j06_setorloc				";
	 	$sql .= "	 left join iptubaixa         on j02_matric       = j01_matric  				";

		$sql2 = "";

		if($dbwhere==""){
		  if($j01_matric!=null ){
		    $sql2 .= " where iptubase.j01_matric = $j01_matric ";
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


   function consultaDebitosMatricula($iMatric=""){

	 require_once(modification("libs/db_utils.php"));

	 $oDaoArrematric = db_utils::getDao("arrematric");

	 $sCampos  = " distinct cadtipo.k03_tipo, ";
   	 $sCampos .= "	        cadtipo.k03_descr ";

   	 $sWhere   = " arrematric.k00_matric = {$iMatric}";

	 $rsConsulta = $oDaoArrematric->sql_record($oDaoArrematric->sql_query_info(null,null,$sCampos,null,$sWhere));

     $aRetorno   = db_utils::getCollectionByRecord($rsConsulta,false,false,false);

  	 return $aRetorno;

   }

	 function sql_query_proprietariolote($sWhere = null) {


		 $sql =  "select proprietario.*,
  	                 j50_descr,
		  								-- Retorna o valor do lote de acordo com o fracionamento
		 						 	   round(((round((select rnfracao
		 				                          from fc_iptu_fracionalote(j01_matric,".db_getsession("DB_datausu").",true,false,false)),10)
						                * lote.j34_area)/100),10) as area_matric,
										ll.j34_descr,
										c.z01_nome   as promitente,
										c.z01_ender  as ender_promitente,
										j.z01_nome   as imobiliaria,
										j.z01_ender  as ender_imobiliaria,
										j.z01_numcgm as z01_numimob,
										lote.j34_totcon,
										iptubaseregimovel.*,
									  setor.j30_descr,
									  loteloc.j06_setorloc,
									  loteloc.j06_quadraloc,
									  loteloc.j06_lote,
									  setorloc.j05_descr,
									  setorloc.j05_codigoproprio,
									  ruastipo.j88_descricao as ruadescricao
 							 from proprietario
				      inner       join lote              on proprietario.j01_idbql   = lote.j34_idbql
               left outer join cgm c             on j41_numcgm               = c.z01_numcgm
               left outer join cgm j             on j44_numcgm               = j.z01_numcgm
							 left outer join loteloteam l      on l.j34_idbql              = proprietario.j01_idbql
						   left outer join loteam ll         on ll.j34_loteam            = l.j34_loteam
               left       join iptubaseregimovel on j01_matric               = j04_matric
		        	 left       join zonas             on lote.j34_zona            = zonas.j50_zona
		        	 left       join setor             on setor.j30_codi           = lote.j34_setor
		        	 left       join loteloc           on lote.j34_idbql           = loteloc.j06_idbql
		        	 left       join setorloc          on setorloc.j05_codigo      = loteloc.j06_setorloc
               left       join ruas              on proprietario.j14_codigo  = ruas.j14_codigo
               left       join ruastipo          on ruastipo.j88_codigo      = ruas.j14_tipo
 							where $sWhere
 							limit 1";
	  //die($sql);
		return $sql;

	}

  function sql_query_area_total($sSetor, $sQuadra, $sLote) {

  	$sql = "select coalesce(sum(j34_area), 0) as area_total
		          from (
	           				select distinct j34_idbql, j34_area
	           					from lote
	           				 inner join iptubase on  j01_idbql = j34_idbql
	           				 where j34_setor  = '$sSetor'
	             				 and j34_quadra = '$sQuadra'
	             				 and j34_lote   = '$sLote'
	             				 and j01_baixa is null ) as x";
  	return $sql;

	}

  function sql_query_area_contruida($sMatricula) {

  	$sql = "select coalesce(sum(j39_area), 0) as area_construida
	            from iptuconstr
        inner join iptubase on j01_matric = j39_matric
	           where j39_matric = '$sMatricula'
   	           and j39_dtdemo is null
   	           and j01_baixa is null";

  	return $sql;

	}

	function sql_query_imobiliaria($iMatricula, $sCampos = "*") {
		$sql = "select $sCampos
		          from imobil
		          inner join cgm on cgm.z01_numcgm  = imobil.j44_numcgm
		         where j44_matric = $iMatricula";
		return $sql;
	}

	function sql_query_setorfiscal($iMatricula, $sCampos="*") {

		$sql = "select $sCampos
		          from lotesetorfiscal
		         inner join cadastro.setorfiscal on j90_codigo = j91_codigo
		         inner join iptubase             on j01_idbql  = j91_idbql ";
		if($iMatricula != null) {
			$sql .= "where j01_matric = $iMatricula";
		}

		return $sql;

	}

	function sql_query_proprietarios($iMatricula, $lPrincipal = false) {

		$sSql = "select j01_numcgm,                            ";
    $sSql.= "       z01_nome,                              ";
    $sSql.= "       true as principal                      ";
    $sSql.= "  from iptubase                               ";
    $sSql.= " inner join cgm on z01_numcgm = j01_numcgm    ";
    $sSql.= " where j01_matric = {$iMatricula}             ";

    if ($lPrincipal) {
	    $sSql.= "                                              ";
	    $sSql.= "   union                                      ";
	    $sSql.= "                                              ";
	    $sSql.= "select j42_numcgm as j01_numcgm,              ";
	    $sSql.= "       z01_nome,                              ";
	    $sSql.= "       false as principal                     ";
	    $sSql.= "  from propri                                 ";
	    $sSql.= " inner join cgm on z01_numcgm = j42_numcgm    ";
	    $sSql.= "where j42_matric = {$iMatricula}              ";
    }

    return $sSql;
	}

	function sql_query_promitentes($iMatricula, $lPrincipal = false) {

		$sSql = "select j41_numcgm,                            ";
		$sSql.= "       z01_nome,                              ";
		$sSql.= "       j41_tipopro as principal,              ";
		$sSql.= "       j41_promitipo                          ";
		$sSql.= "  from promitente                             ";
		$sSql.= " inner join cgm on z01_numcgm = j41_numcgm    ";
		$sSql.= "where j41_matric = {$iMatricula}              ";

		if ($lPrincipal) {
			$sSql .= " and j41_tipopro is true";
		}

		return $sSql;
	}

	function sql_query_construcoes ( $j01_matric = null, $campos = "*", $ordem = null, $dbwhere = "") {

	  $sql = "select ";
	  if ($campos != "*" ) {

	    $campos_sql = split("#",$campos);
	    $virgula = "";
	    for ($i = 0; $i <sizeof($campos_sql); $i++) {

	      $sql .= $virgula.$campos_sql[$i];
	      $virgula = ",";
	    }

	  }else{
	    $sql .= $campos;
	  }

	  $sql .= " from iptubase ";
	  $sql .= "      inner join lote       on lote.j34_idbql      = iptubase.j01_idbql";
	  $sql .= "      inner join cgm        on cgm.z01_numcgm      = iptubase.j01_numcgm";
	  $sql .= "      inner join bairro     on bairro.j13_codi     = lote.j34_bairro";
	  $sql .= "      inner join setor      on setor.j30_codi      = lote.j34_setor";
	  $sql .= "      inner join iptuconstr on iptubase.j01_matric = iptuconstr.j39_matric";
	  $sql .= "       left join iptuant    on iptubase.j01_matric = iptuant.j40_matric";
	  $sql2 = "";
	  if ($dbwhere=="") {

	    if ($j01_matric != null ) {
	      $sql2 .= " where iptubase.j01_matric = $j01_matric ";
	    }

	  } else if ($dbwhere != "") {
	    $sql2 = " where $dbwhere";
	  }

	  $sql .= $sql2;
	  if($ordem != null ){
	    $sql .= " order by ";
	    $campos_sql = split("#",$ordem);
	    $virgula    = "";
	    for ($i = 0; $i < sizeof($campos_sql); $i++) {

	      $sql .= $virgula.$campos_sql[$i];
	      $virgula = ",";
	    }
	  }
	  return $sql;
	}

	function sql_queryCalculoMatricula ($iMatricula, $iAnousu) {

	  $sCampos  = "j23_anousu     , ";
	  $sCampos .= "j23_matric     , ";
	  $sCampos .= "j23_testad     , ";
	  $sCampos .= "j23_arealo     , ";
	  $sCampos .= "j23_areafr     , ";
	  $sCampos .= "j23_areaed     , ";
	  $sCampos .= "j23_m2terr     , ";
	  $sCampos .= "j23_vlrter     , ";
	  $sCampos .= "j23_aliq       , ";
	  $sCampos .= "j23_vlrisen    , ";
	  $sCampos .= "j23_tipoim     , ";
	  $sCampos .= "j23_tipocalculo, ";
	  $sCampos .= "j22_idcons     , ";
	  $sCampos .= "j22_areaed     , ";
	  $sCampos .= "j22_vm2        , ";
	  $sCampos .= "j22_pontos     , ";
	  $sCampos .= "j22_valor      , ";
	  $sCampos .= "j21_receit     , ";
	  $sCampos .= "j21_valor      , ";
	  $sCampos .= "j21_quant      , ";
	  $sCampos .= "j21_codhis     , ";
	  $sCampos .= "j20_numpre       ";

	  $sSql    = "select {$sCampos}                                                 ";
	  $sSql   .= "  from iptucalc                                                   ";
	  $sSql   .= " inner join iptucale on iptucale.j22_anousu = iptucalc.j23_anousu ";
	  $sSql   .= "  								  and iptucale.j22_matric = iptucalc.j23_matric ";
	  $sSql   .= " inner join iptucalv on iptucalv.j21_anousu = iptucalc.j23_anousu ";
	  $sSql   .= "                    and iptucalv.j21_matric = iptucalc.j23_matric ";
	  $sSql   .= " inner join iptunump on iptunump.j20_anousu = iptucalc.j23_anousu ";
	  $sSql   .= "									  and iptunump.j20_matric = iptucalc.j23_matric ";
    $sSql   .= " where iptucalc.j23_anousu = {$iAnousu}				                    ";
    $sSql   .= "   and iptucalc.j23_matric = {$iMatricula}  			                ";

    return $sSql;

	}

	function sql_query_enderecoEntrega($iNumCgmProprietario = null, $sCampos = "*", $sWhere = null) {

		if (empty($sWhere) && !empty($iNumCgmProprietario)) {
			$sWhere = " where j01_numcgm = {$iNumCgmProprietario} ";
		} else if (!empty($sWhere) && !empty($iNumCgmProprietario)) {
			$sWhere = " where {$sWhere} and j01_numcgm = {$iNumCgmProprietario} ";
		}

		$sSql  = "select {$sCampos}                                     ";
		$sSql .= "  from iptubase                                       ";
		$sSql .= "       left join iptuender on j43_matric = j01_matric ";
		$sSql .= $sWhere;
		return $sSql;
	}

  public function findBydId($value) {

    $sSqlQuery = $this->sql_query_file($value);
    $rsDados   = db_query($sSqlQuery);
    if (!$rsDados) {
      throw  new \DBException('Erro ao pesquisar matrícula');
    }
    if (pg_num_rows($rsDados) > 0) {
      return pg_fetch_object($rsDados, 0);
    }
    return null;
  }

}
?>