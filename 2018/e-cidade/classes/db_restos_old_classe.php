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
//CLASSE DA ENTIDADE restos_old
class cl_restos_old {
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
   var $cm12_i_codigo = 0;
   var $cm12_i_ossoariopart = 0;
   var $cm12_i_resto = 0;
   var $cm12_i_sepultamento = 0;
   var $cm12_d_entrada_dia = null;
   var $cm12_d_entrada_mes = null;
   var $cm12_d_entrada_ano = null;
   var $cm12_d_entrada = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 cm12_i_codigo = int4 = Código
                 cm12_i_ossoariopart = int4 = Ossário
                 cm12_i_resto = int4 = Numero
                 cm12_i_sepultamento = int4 = Sepultamento
                 cm12_d_entrada = date = Entrada
                 ";
   //funcao construtor da classe
   function cl_restos_old() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("restos_old");
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
       $this->cm12_i_codigo = ($this->cm12_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm12_i_codigo"]:$this->cm12_i_codigo);
       $this->cm12_i_ossoariopart = ($this->cm12_i_ossoariopart == ""?@$GLOBALS["HTTP_POST_VARS"]["cm12_i_ossoariopart"]:$this->cm12_i_ossoariopart);
       $this->cm12_i_resto = ($this->cm12_i_resto == ""?@$GLOBALS["HTTP_POST_VARS"]["cm12_i_resto"]:$this->cm12_i_resto);
       $this->cm12_i_sepultamento = ($this->cm12_i_sepultamento == ""?@$GLOBALS["HTTP_POST_VARS"]["cm12_i_sepultamento"]:$this->cm12_i_sepultamento);
       if($this->cm12_d_entrada == ""){
         $this->cm12_d_entrada_dia = ($this->cm12_d_entrada_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cm12_d_entrada_dia"]:$this->cm12_d_entrada_dia);
         $this->cm12_d_entrada_mes = ($this->cm12_d_entrada_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm12_d_entrada_mes"]:$this->cm12_d_entrada_mes);
         $this->cm12_d_entrada_ano = ($this->cm12_d_entrada_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cm12_d_entrada_ano"]:$this->cm12_d_entrada_ano);
         if($this->cm12_d_entrada_dia != ""){
            $this->cm12_d_entrada = $this->cm12_d_entrada_ano."-".$this->cm12_d_entrada_mes."-".$this->cm12_d_entrada_dia;
         }
       }
     }else{
       $this->cm12_i_codigo = ($this->cm12_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm12_i_codigo"]:$this->cm12_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($cm12_i_codigo){
      $this->atualizacampos();
     if($this->cm12_i_ossoariopart == null ){
       $this->erro_sql = " Campo Ossário nao Informado.";
       $this->erro_campo = "cm12_i_ossoariopart";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm12_i_resto == null ){
       $this->erro_sql = " Campo Numero nao Informado.";
       $this->erro_campo = "cm12_i_resto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm12_i_sepultamento == null ){
       $this->erro_sql = " Campo Sepultamento nao Informado.";
       $this->erro_campo = "cm12_i_sepultamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm12_d_entrada == null ){
       $this->erro_sql = " Campo Entrada nao Informado.";
       $this->erro_campo = "cm12_d_entrada_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($cm12_i_codigo == "" || $cm12_i_codigo == null ){
       $result = db_query("select nextval('restos_old_cm12_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: restos_old_cm12_i_codigo_seq do campo: cm12_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->cm12_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from restos_old_cm12_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $cm12_i_codigo)){
         $this->erro_sql = " Campo cm12_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cm12_i_codigo = $cm12_i_codigo;
       }
     }
     if(($this->cm12_i_codigo == null) || ($this->cm12_i_codigo == "") ){
       $this->erro_sql = " Campo cm12_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into restos_old(
                                       cm12_i_codigo
                                      ,cm12_i_ossoariopart
                                      ,cm12_i_resto
                                      ,cm12_i_sepultamento
                                      ,cm12_d_entrada
                       )
                values (
                                $this->cm12_i_codigo
                               ,$this->cm12_i_ossoariopart
                               ,$this->cm12_i_resto
                               ,$this->cm12_i_sepultamento
                               ,".($this->cm12_d_entrada == "null" || $this->cm12_d_entrada == ""?"null":"'".$this->cm12_d_entrada."'")."
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Restos Old ($this->cm12_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Restos Old já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Restos Old ($this->cm12_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm12_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cm12_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10415,'$this->cm12_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1800,10415,'','".AddSlashes(pg_result($resaco,0,'cm12_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1800,10416,'','".AddSlashes(pg_result($resaco,0,'cm12_i_ossoariopart'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1800,10417,'','".AddSlashes(pg_result($resaco,0,'cm12_i_resto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1800,10418,'','".AddSlashes(pg_result($resaco,0,'cm12_i_sepultamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1800,10419,'','".AddSlashes(pg_result($resaco,0,'cm12_d_entrada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($cm12_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update restos_old set ";
     $virgula = "";
     if(trim($this->cm12_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm12_i_codigo"])){
       $sql  .= $virgula." cm12_i_codigo = $this->cm12_i_codigo ";
       $virgula = ",";
       if(trim($this->cm12_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "cm12_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm12_i_ossoariopart)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm12_i_ossoariopart"])){
       $sql  .= $virgula." cm12_i_ossoariopart = $this->cm12_i_ossoariopart ";
       $virgula = ",";
       if(trim($this->cm12_i_ossoariopart) == null ){
         $this->erro_sql = " Campo Ossário nao Informado.";
         $this->erro_campo = "cm12_i_ossoariopart";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm12_i_resto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm12_i_resto"])){
       $sql  .= $virgula." cm12_i_resto = $this->cm12_i_resto ";
       $virgula = ",";
       if(trim($this->cm12_i_resto) == null ){
         $this->erro_sql = " Campo Numero nao Informado.";
         $this->erro_campo = "cm12_i_resto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm12_i_sepultamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm12_i_sepultamento"])){
       $sql  .= $virgula." cm12_i_sepultamento = $this->cm12_i_sepultamento ";
       $virgula = ",";
       if(trim($this->cm12_i_sepultamento) == null ){
         $this->erro_sql = " Campo Sepultamento nao Informado.";
         $this->erro_campo = "cm12_i_sepultamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm12_d_entrada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm12_d_entrada_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cm12_d_entrada_dia"] !="") ){
       $sql  .= $virgula." cm12_d_entrada = '$this->cm12_d_entrada' ";
       $virgula = ",";
       if(trim($this->cm12_d_entrada) == null ){
         $this->erro_sql = " Campo Entrada nao Informado.";
         $this->erro_campo = "cm12_d_entrada_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["cm12_d_entrada_dia"])){
         $sql  .= $virgula." cm12_d_entrada = null ";
         $virgula = ",";
         if(trim($this->cm12_d_entrada) == null ){
           $this->erro_sql = " Campo Entrada nao Informado.";
           $this->erro_campo = "cm12_d_entrada_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($cm12_i_codigo!=null){
       $sql .= " cm12_i_codigo = $this->cm12_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cm12_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10415,'$this->cm12_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm12_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1800,10415,'".AddSlashes(pg_result($resaco,$conresaco,'cm12_i_codigo'))."','$this->cm12_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm12_i_ossoariopart"]))
           $resac = db_query("insert into db_acount values($acount,1800,10416,'".AddSlashes(pg_result($resaco,$conresaco,'cm12_i_ossoariopart'))."','$this->cm12_i_ossoariopart',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm12_i_resto"]))
           $resac = db_query("insert into db_acount values($acount,1800,10417,'".AddSlashes(pg_result($resaco,$conresaco,'cm12_i_resto'))."','$this->cm12_i_resto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm12_i_sepultamento"]))
           $resac = db_query("insert into db_acount values($acount,1800,10418,'".AddSlashes(pg_result($resaco,$conresaco,'cm12_i_sepultamento'))."','$this->cm12_i_sepultamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm12_d_entrada"]))
           $resac = db_query("insert into db_acount values($acount,1800,10419,'".AddSlashes(pg_result($resaco,$conresaco,'cm12_d_entrada'))."','$this->cm12_d_entrada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Restos Old nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm12_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Restos Old nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm12_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm12_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($cm12_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cm12_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10415,'$cm12_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1800,10415,'','".AddSlashes(pg_result($resaco,$iresaco,'cm12_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1800,10416,'','".AddSlashes(pg_result($resaco,$iresaco,'cm12_i_ossoariopart'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1800,10417,'','".AddSlashes(pg_result($resaco,$iresaco,'cm12_i_resto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1800,10418,'','".AddSlashes(pg_result($resaco,$iresaco,'cm12_i_sepultamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1800,10419,'','".AddSlashes(pg_result($resaco,$iresaco,'cm12_d_entrada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from restos_old
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cm12_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cm12_i_codigo = $cm12_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Restos Old nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cm12_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Restos Old nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cm12_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cm12_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:restos_old";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $cm12_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from restos_old ";
     $sql .= "      inner join ossoariopart  on  ossoariopart.cm02_i_codigo = restos_old.cm12_i_ossoariopart";
     $sql .= "      inner join sepultamentos  on  sepultamentos.cm01_i_codigo = restos_old.cm12_i_sepultamento";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = ossoariopart.cm02_i_proprietario";
     $sql .= "      inner join protprocesso  on  protprocesso.p58_codproc = ossoariopart.cm02_i_processo";
     $sql .= "      inner join cgm  as a on   a.z01_numcgm = sepultamentos.cm01_i_codigo";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = sepultamentos.cm01_i_funcionario";
     $sql .= "      inner join causa  on  causa.cm04_i_codigo = sepultamentos.cm01_i_causa";
     $sql .= "      inner join cemiterio  on  cemiterio.cm14_i_codigo = sepultamentos.cm01_i_cemiterio";
     $sql .= "      left  join funerarias  on  funerarias.cm17_i_funeraria = sepultamentos.cm01_i_funeraria";
     $sql .= "      left  join hospitais  on  hospitais.cm18_i_hospital = sepultamentos.cm01_i_hospital";
     $sql2 = "";
     if($dbwhere==""){
       if($cm12_i_codigo!=null ){
         $sql2 .= " where restos_old.cm12_i_codigo = $cm12_i_codigo ";
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
   function sql_query_file ( $cm12_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from restos_old ";
     $sql2 = "";
     if($dbwhere==""){
       if($cm12_i_codigo!=null ){
         $sql2 .= " where restos_old.cm12_i_codigo = $cm12_i_codigo ";
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
