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

//MODULO: Cemiterio
//CLASSE DA ENTIDADE gavetas
class cl_gavetas {
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
   var $cm27_i_codigo = 0;
   var $cm27_i_restogaveta = 0;
   var $cm27_d_exumprevista_dia = null;
   var $cm27_d_exumprevista_mes = null;
   var $cm27_d_exumprevista_ano = null;
   var $cm27_d_exumprevista = null;
   var $cm27_d_exumfeita_dia = null;
   var $cm27_d_exumfeita_mes = null;
   var $cm27_d_exumfeita_ano = null;
   var $cm27_d_exumfeita = null;
   var $cm27_c_ossoario = null;
   var $cm27_i_gaveta = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 cm27_i_codigo = int4 = Código
                 cm27_i_restogaveta = int4 = Resto/Gaveta
                 cm27_d_exumprevista = date = Exumação Prevista
                 cm27_d_exumfeita = date = Exumação Feita
                 cm27_c_ossoario = char(1) = Está no Ossário
                 cm27_i_gaveta = int4 = N da Gaveta
                 ";
   //funcao construtor da classe
   function cl_gavetas() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("gavetas");
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
       $this->cm27_i_codigo = ($this->cm27_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm27_i_codigo"]:$this->cm27_i_codigo);
       $this->cm27_i_restogaveta = ($this->cm27_i_restogaveta == ""?@$GLOBALS["HTTP_POST_VARS"]["cm27_i_restogaveta"]:$this->cm27_i_restogaveta);
       if($this->cm27_d_exumprevista == ""){
         $this->cm27_d_exumprevista_dia = ($this->cm27_d_exumprevista_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cm27_d_exumprevista_dia"]:$this->cm27_d_exumprevista_dia);
         $this->cm27_d_exumprevista_mes = ($this->cm27_d_exumprevista_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm27_d_exumprevista_mes"]:$this->cm27_d_exumprevista_mes);
         $this->cm27_d_exumprevista_ano = ($this->cm27_d_exumprevista_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cm27_d_exumprevista_ano"]:$this->cm27_d_exumprevista_ano);
         if($this->cm27_d_exumprevista_dia != ""){
            $this->cm27_d_exumprevista = $this->cm27_d_exumprevista_ano."-".$this->cm27_d_exumprevista_mes."-".$this->cm27_d_exumprevista_dia;
         }
       }
       if($this->cm27_d_exumfeita == ""){
         $this->cm27_d_exumfeita_dia = ($this->cm27_d_exumfeita_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cm27_d_exumfeita_dia"]:$this->cm27_d_exumfeita_dia);
         $this->cm27_d_exumfeita_mes = ($this->cm27_d_exumfeita_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm27_d_exumfeita_mes"]:$this->cm27_d_exumfeita_mes);
         $this->cm27_d_exumfeita_ano = ($this->cm27_d_exumfeita_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cm27_d_exumfeita_ano"]:$this->cm27_d_exumfeita_ano);
         if($this->cm27_d_exumfeita_dia != ""){
            $this->cm27_d_exumfeita = $this->cm27_d_exumfeita_ano."-".$this->cm27_d_exumfeita_mes."-".$this->cm27_d_exumfeita_dia;
         }
       }
       $this->cm27_c_ossoario = ($this->cm27_c_ossoario == ""?@$GLOBALS["HTTP_POST_VARS"]["cm27_c_ossoario"]:$this->cm27_c_ossoario);
       $this->cm27_i_gaveta = ($this->cm27_i_gaveta == ""?@$GLOBALS["HTTP_POST_VARS"]["cm27_i_gaveta"]:$this->cm27_i_gaveta);
     }else{
       $this->cm27_i_codigo = ($this->cm27_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm27_i_codigo"]:$this->cm27_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($cm27_i_codigo){
      $this->atualizacampos();
     if($this->cm27_i_restogaveta == null ){
       $this->erro_sql = " Campo Resto/Gaveta nao Informado.";
       $this->erro_campo = "cm27_i_restogaveta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm27_d_exumprevista == null ){
       $this->erro_sql = " Campo Exumação Prevista nao Informado.";
       $this->erro_campo = "cm27_d_exumprevista_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm27_c_ossoario == null ){
       $this->cm27_c_ossoario = "N";
     }
     if($this->cm27_i_gaveta == null ){
       $this->cm27_i_gaveta = 0;
     }
     if($cm27_i_codigo == "" || $cm27_i_codigo == null ){
       $result = db_query("select nextval('gavetas_cm27_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: gavetas_cm27_i_codigo_seq do campo: cm27_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->cm27_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from gavetas_cm27_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $cm27_i_codigo)){
         $this->erro_sql = " Campo cm27_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cm27_i_codigo = $cm27_i_codigo;
       }
     }
     if(($this->cm27_i_codigo == null) || ($this->cm27_i_codigo == "") ){
       $this->erro_sql = " Campo cm27_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into gavetas(
                                       cm27_i_codigo
                                      ,cm27_i_restogaveta
                                      ,cm27_d_exumprevista
                                      ,cm27_d_exumfeita
                                      ,cm27_c_ossoario
                                      ,cm27_i_gaveta
                       )
                values (
                                $this->cm27_i_codigo
                               ,$this->cm27_i_restogaveta
                               ,".($this->cm27_d_exumprevista == "null" || $this->cm27_d_exumprevista == ""?"null":"'".$this->cm27_d_exumprevista."'")."
                               ,".($this->cm27_d_exumfeita == "null" || $this->cm27_d_exumfeita == ""?"null":"'".$this->cm27_d_exumfeita."'")."
                               ,'$this->cm27_c_ossoario'
                               ,$this->cm27_i_gaveta
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Gavetas do Jazigo ($this->cm27_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Gavetas do Jazigo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Gavetas do Jazigo ($this->cm27_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm27_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cm27_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10303,'$this->cm27_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1784,10303,'','".AddSlashes(pg_result($resaco,0,'cm27_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1784,10304,'','".AddSlashes(pg_result($resaco,0,'cm27_i_restogaveta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1784,10305,'','".AddSlashes(pg_result($resaco,0,'cm27_d_exumprevista'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1784,10306,'','".AddSlashes(pg_result($resaco,0,'cm27_d_exumfeita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1784,10307,'','".AddSlashes(pg_result($resaco,0,'cm27_c_ossoario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1784,10308,'','".AddSlashes(pg_result($resaco,0,'cm27_i_gaveta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($cm27_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update gavetas set ";
     $virgula = "";
     if(trim($this->cm27_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm27_i_codigo"])){
       $sql  .= $virgula." cm27_i_codigo = $this->cm27_i_codigo ";
       $virgula = ",";
       if(trim($this->cm27_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "cm27_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm27_i_restogaveta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm27_i_restogaveta"])){
       if(trim($this->cm27_i_restogaveta) == null ){
         $this->cm27_i_restogaveta = 0;
       }
       $sql  .= $virgula." cm27_i_restogaveta = $this->cm27_i_restogaveta ";
       $virgula = ",";
     }
     if(trim($this->cm27_d_exumprevista)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm27_d_exumprevista_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cm27_d_exumprevista_dia"] !="") ){
       $sql  .= $virgula." cm27_d_exumprevista = '$this->cm27_d_exumprevista' ";
       $virgula = ",";
       if(trim($this->cm27_d_exumprevista) == null ){
         $this->erro_sql = " Campo Exumação Prevista nao Informado.";
         $this->erro_campo = "cm27_d_exumprevista_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["cm27_d_exumprevista_dia"])){
         $sql  .= $virgula." cm27_d_exumprevista = null ";
         $virgula = ",";
         if(trim($this->cm27_d_exumprevista) == null ){
           $this->erro_sql = " Campo Exumação Prevista nao Informado.";
           $this->erro_campo = "cm27_d_exumprevista_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->cm27_d_exumfeita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm27_d_exumfeita_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cm27_d_exumfeita_dia"] !="") ){
       $sql  .= $virgula." cm27_d_exumfeita = '$this->cm27_d_exumfeita' ";
       $virgula = ",";
     }
     if(trim($this->cm27_c_ossoario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm27_c_ossoario"])){
       $sql  .= $virgula." cm27_c_ossoario = '$this->cm27_c_ossoario' ";
       $virgula = ",";
     }
     if(trim($this->cm27_i_gaveta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm27_i_gaveta"])){
       if(trim($this->cm27_i_gaveta) == null ){
         $this->cm27_i_gaveta = 0;
       }
       $sql  .= $virgula." cm27_i_gaveta = $this->cm27_i_gaveta ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($cm27_i_codigo!=null){
       $sql .= " cm27_i_codigo = $this->cm27_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cm27_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10303,'$this->cm27_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm27_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1784,10303,'".AddSlashes(pg_result($resaco,$conresaco,'cm27_i_codigo'))."','$this->cm27_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm27_i_restogaveta"]))
           $resac = db_query("insert into db_acount values($acount,1784,10304,'".AddSlashes(pg_result($resaco,$conresaco,'cm27_i_restogaveta'))."','$this->cm27_i_restogaveta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm27_d_exumprevista"]))
           $resac = db_query("insert into db_acount values($acount,1784,10305,'".AddSlashes(pg_result($resaco,$conresaco,'cm27_d_exumprevista'))."','$this->cm27_d_exumprevista',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm27_d_exumfeita"]))
           $resac = db_query("insert into db_acount values($acount,1784,10306,'".AddSlashes(pg_result($resaco,$conresaco,'cm27_d_exumfeita'))."','$this->cm27_d_exumfeita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm27_c_ossoario"]))
           $resac = db_query("insert into db_acount values($acount,1784,10307,'".AddSlashes(pg_result($resaco,$conresaco,'cm27_c_ossoario'))."','$this->cm27_c_ossoario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm27_i_gaveta"]))
           $resac = db_query("insert into db_acount values($acount,1784,10308,'".AddSlashes(pg_result($resaco,$conresaco,'cm27_i_gaveta'))."','$this->cm27_i_gaveta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Gavetas do Jazigo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm27_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Gavetas do Jazigo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm27_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm27_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($cm27_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cm27_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10303,'$cm27_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1784,10303,'','".AddSlashes(pg_result($resaco,$iresaco,'cm27_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1784,10304,'','".AddSlashes(pg_result($resaco,$iresaco,'cm27_i_restogaveta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1784,10305,'','".AddSlashes(pg_result($resaco,$iresaco,'cm27_d_exumprevista'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1784,10306,'','".AddSlashes(pg_result($resaco,$iresaco,'cm27_d_exumfeita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1784,10307,'','".AddSlashes(pg_result($resaco,$iresaco,'cm27_c_ossoario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1784,10308,'','".AddSlashes(pg_result($resaco,$iresaco,'cm27_i_gaveta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from gavetas
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cm27_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cm27_i_codigo = $cm27_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Gavetas do Jazigo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cm27_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Gavetas do Jazigo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cm27_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cm27_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:gavetas";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $cm27_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from gavetas ";
     $sql .= "      inner join restosgavetas  on  restosgavetas.cm26_i_codigo = gavetas.cm27_i_restogaveta";
     $sql .= "      inner join ossoariojazigo  on  ossoariojazigo.cm25_i_codigo = restosgavetas.cm26_i_ossoariojazigo";
     $sql .= "      inner join sepultamentos  on  sepultamentos.cm01_i_codigo = restosgavetas.cm26_i_sepultamento";
     $sql2 = "";
     if($dbwhere==""){
       if($cm27_i_codigo!=null ){
         $sql2 .= " where gavetas.cm27_i_codigo = $cm27_i_codigo ";
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
   function sql_query_file ( $cm27_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from gavetas ";
     $sql2 = "";
     if($dbwhere==""){
       if($cm27_i_codigo!=null ){
         $sql2 .= " where gavetas.cm27_i_codigo = $cm27_i_codigo ";
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
}
?>