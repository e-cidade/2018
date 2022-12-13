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
//CLASSE DA ENTIDADE sepulta
class cl_sepulta {
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
   var $cm24_i_codigo = 0;
   var $cm24_i_sepultura = 0;
   var $cm24_i_sepultamento = 0;
   var $cm24_d_entrada_dia = null;
   var $cm24_d_entrada_mes = null;
   var $cm24_d_entrada_ano = null;
   var $cm24_d_entrada = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 cm24_i_codigo = int4 = Código
                 cm24_i_sepultura = int4 = Sepultura
                 cm24_i_sepultamento = int4 = Sepultamento
                 cm24_d_entrada = date = Entrada
                 ";
   //funcao construtor da classe
   function cl_sepulta() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sepulta");
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
       $this->cm24_i_codigo = ($this->cm24_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm24_i_codigo"]:$this->cm24_i_codigo);
       $this->cm24_i_sepultura = ($this->cm24_i_sepultura == ""?@$GLOBALS["HTTP_POST_VARS"]["cm24_i_sepultura"]:$this->cm24_i_sepultura);
       $this->cm24_i_sepultamento = ($this->cm24_i_sepultamento == ""?@$GLOBALS["HTTP_POST_VARS"]["cm24_i_sepultamento"]:$this->cm24_i_sepultamento);
       if($this->cm24_d_entrada == ""){
         $this->cm24_d_entrada_dia = ($this->cm24_d_entrada_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cm24_d_entrada_dia"]:$this->cm24_d_entrada_dia);
         $this->cm24_d_entrada_mes = ($this->cm24_d_entrada_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm24_d_entrada_mes"]:$this->cm24_d_entrada_mes);
         $this->cm24_d_entrada_ano = ($this->cm24_d_entrada_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cm24_d_entrada_ano"]:$this->cm24_d_entrada_ano);
         if($this->cm24_d_entrada_dia != ""){
            $this->cm24_d_entrada = $this->cm24_d_entrada_ano."-".$this->cm24_d_entrada_mes."-".$this->cm24_d_entrada_dia;
         }
       }
     }else{
       $this->cm24_i_codigo = ($this->cm24_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm24_i_codigo"]:$this->cm24_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($cm24_i_codigo){
      $this->atualizacampos();
     if($this->cm24_i_sepultura == null ){
       $this->erro_sql = " Campo Sepultura nao Informado.";
       $this->erro_campo = "cm24_i_sepultura";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm24_i_sepultamento == null ){
       $this->erro_sql = " Campo Sepultamento nao Informado.";
       $this->erro_campo = "cm24_i_sepultamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm24_d_entrada == null ){
       $this->erro_sql = " Campo Entrada nao Informado.";
       $this->erro_campo = "cm24_d_entrada_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($cm24_i_codigo == "" || $cm24_i_codigo == null ){
       $result = db_query("select nextval('sepulta_cm24_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sepulta_cm24_i_codigo_seq do campo: cm24_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->cm24_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from sepulta_cm24_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $cm24_i_codigo)){
         $this->erro_sql = " Campo cm24_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cm24_i_codigo = $cm24_i_codigo;
       }
     }
     if(($this->cm24_i_codigo == null) || ($this->cm24_i_codigo == "") ){
       $this->erro_sql = " Campo cm24_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sepulta(
                                       cm24_i_codigo
                                      ,cm24_i_sepultura
                                      ,cm24_i_sepultamento
                                      ,cm24_d_entrada
                       )
                values (
                                $this->cm24_i_codigo
                               ,$this->cm24_i_sepultura
                               ,$this->cm24_i_sepultamento
                               ,".($this->cm24_d_entrada == "null" || $this->cm24_d_entrada == ""?"null":"'".$this->cm24_d_entrada."'")."
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Sepulta ($this->cm24_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Sepulta já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Sepulta ($this->cm24_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm24_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cm24_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10428,'$this->cm24_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1803,10428,'','".AddSlashes(pg_result($resaco,0,'cm24_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1803,10432,'','".AddSlashes(pg_result($resaco,0,'cm24_i_sepultura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1803,10433,'','".AddSlashes(pg_result($resaco,0,'cm24_i_sepultamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1803,10434,'','".AddSlashes(pg_result($resaco,0,'cm24_d_entrada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($cm24_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update sepulta set ";
     $virgula = "";
     if(trim($this->cm24_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm24_i_codigo"])){
       $sql  .= $virgula." cm24_i_codigo = $this->cm24_i_codigo ";
       $virgula = ",";
       if(trim($this->cm24_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "cm24_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm24_i_sepultura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm24_i_sepultura"])){
       $sql  .= $virgula." cm24_i_sepultura = $this->cm24_i_sepultura ";
       $virgula = ",";
       if(trim($this->cm24_i_sepultura) == null ){
         $this->erro_sql = " Campo Sepultura nao Informado.";
         $this->erro_campo = "cm24_i_sepultura";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm24_i_sepultamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm24_i_sepultamento"])){
       $sql  .= $virgula." cm24_i_sepultamento = $this->cm24_i_sepultamento ";
       $virgula = ",";
       if(trim($this->cm24_i_sepultamento) == null ){
         $this->erro_sql = " Campo Sepultamento nao Informado.";
         $this->erro_campo = "cm24_i_sepultamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm24_d_entrada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm24_d_entrada_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cm24_d_entrada_dia"] !="") ){
       $sql  .= $virgula." cm24_d_entrada = '$this->cm24_d_entrada' ";
       $virgula = ",";
       if(trim($this->cm24_d_entrada) == null ){
         $this->erro_sql = " Campo Entrada nao Informado.";
         $this->erro_campo = "cm24_d_entrada_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["cm24_d_entrada_dia"])){
         $sql  .= $virgula." cm24_d_entrada = null ";
         $virgula = ",";
         if(trim($this->cm24_d_entrada) == null ){
           $this->erro_sql = " Campo Entrada nao Informado.";
           $this->erro_campo = "cm24_d_entrada_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($cm24_i_codigo!=null){
       $sql .= " cm24_i_codigo = $this->cm24_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cm24_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10428,'$this->cm24_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm24_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1803,10428,'".AddSlashes(pg_result($resaco,$conresaco,'cm24_i_codigo'))."','$this->cm24_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm24_i_sepultura"]))
           $resac = db_query("insert into db_acount values($acount,1803,10432,'".AddSlashes(pg_result($resaco,$conresaco,'cm24_i_sepultura'))."','$this->cm24_i_sepultura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm24_i_sepultamento"]))
           $resac = db_query("insert into db_acount values($acount,1803,10433,'".AddSlashes(pg_result($resaco,$conresaco,'cm24_i_sepultamento'))."','$this->cm24_i_sepultamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm24_d_entrada"]))
           $resac = db_query("insert into db_acount values($acount,1803,10434,'".AddSlashes(pg_result($resaco,$conresaco,'cm24_d_entrada'))."','$this->cm24_d_entrada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Sepulta nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm24_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Sepulta nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm24_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm24_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($cm24_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cm24_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10428,'$cm24_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1803,10428,'','".AddSlashes(pg_result($resaco,$iresaco,'cm24_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1803,10432,'','".AddSlashes(pg_result($resaco,$iresaco,'cm24_i_sepultura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1803,10433,'','".AddSlashes(pg_result($resaco,$iresaco,'cm24_i_sepultamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1803,10434,'','".AddSlashes(pg_result($resaco,$iresaco,'cm24_d_entrada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sepulta
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cm24_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cm24_i_codigo = $cm24_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Sepulta nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cm24_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Sepulta nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cm24_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cm24_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:sepulta";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $cm24_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from sepulta ";
     $sql .= "      inner join sepultamentos  on  sepultamentos.cm01_i_codigo = sepulta.cm24_i_sepultamento";
     $sql .= "      inner join sepulturas  on  sepulturas.cm05_i_codigo = sepulta.cm24_i_sepultura";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = sepultamentos.cm01_i_codigo";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = sepultamentos.cm01_i_funcionario";
     $sql .= "      left join legista       on  legista.cm32_i_codigo = sepultamentos.cm01_i_medico";
     $sql .= "      inner join causa  on  causa.cm04_i_codigo = sepultamentos.cm01_i_causa";
     $sql .= "      inner join cemiterio  on  cemiterio.cm14_i_codigo = sepultamentos.cm01_i_cemiterio";
     $sql .= "      left  join funerarias  on  funerarias.cm17_i_funeraria = sepultamentos.cm01_i_funeraria";
     $sql .= "      left join hospitais  on  hospitais.cm18_i_hospital = sepultamentos.cm01_i_hospital";
     $sql .= "      inner join campas  on  campas.cm19_i_codigo = sepulturas.cm05_i_campa";
     $sql .= "      inner join lotecemit  on  lotecemit.cm23_i_codigo = sepulturas.cm05_i_lotecemit";

     $sql .= "      inner join quadracemit   on  quadracemit.cm22_i_codigo = lotecemit.cm23_i_quadracemit";
     //$sql .= "      inner join cemiterio     on  cemiterio.cm14_i_codigo = quadracemit.cm22_i_cemiterio";
     $sql .= " left join cemiteriorural      on cm14_i_codigo = cm16_i_cemiterio";
     $sql .= " left join cemiteriocgm        on cm14_i_codigo = cm15_i_cemiterio";
     $sql .= " left join cgm cgmcemit        on cgmcemit.z01_numcgm = cm15_i_cgm";



     $sql2 = "";
     if($dbwhere==""){
       if($cm24_i_codigo!=null ){
         $sql2 .= " where sepulta.cm24_i_codigo = $cm24_i_codigo ";
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
   function sql_query_file ( $cm24_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from sepulta ";
     $sql2 = "";
     if($dbwhere==""){
       if($cm24_i_codigo!=null ){
         $sql2 .= " where sepulta.cm24_i_codigo = $cm24_i_codigo ";
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
