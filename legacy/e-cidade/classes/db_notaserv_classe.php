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

//MODULO: cemiterio
//CLASSE DA ENTIDADE notaserv
class cl_notaserv {
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
   var $cm09_i_codigo = 0;
   var $cm09_i_sepultamento = 0;
   var $cm09_d_emissao_dia = null;
   var $cm09_d_emissao_mes = null;
   var $cm09_d_emissao_ano = null;
   var $cm09_d_emissao = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 cm09_i_codigo = int8 = Código
                 cm09_i_sepultamento = int8 = Código Sepultamento
                 cm09_d_emissao = date = Emissão
                 ";
   //funcao construtor da classe
   function cl_notaserv() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("notaserv");
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
       $this->cm09_i_codigo = ($this->cm09_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm09_i_codigo"]:$this->cm09_i_codigo);
       $this->cm09_i_sepultamento = ($this->cm09_i_sepultamento == ""?@$GLOBALS["HTTP_POST_VARS"]["cm09_i_sepultamento"]:$this->cm09_i_sepultamento);
       if($this->cm09_d_emissao == ""){
         $this->cm09_d_emissao_dia = ($this->cm09_d_emissao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cm09_d_emissao_dia"]:$this->cm09_d_emissao_dia);
         $this->cm09_d_emissao_mes = ($this->cm09_d_emissao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm09_d_emissao_mes"]:$this->cm09_d_emissao_mes);
         $this->cm09_d_emissao_ano = ($this->cm09_d_emissao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cm09_d_emissao_ano"]:$this->cm09_d_emissao_ano);
         if($this->cm09_d_emissao_dia != ""){
            $this->cm09_d_emissao = $this->cm09_d_emissao_ano."-".$this->cm09_d_emissao_mes."-".$this->cm09_d_emissao_dia;
         }
       }
     }else{
       $this->cm09_i_codigo = ($this->cm09_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm09_i_codigo"]:$this->cm09_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($cm09_i_codigo){
      $this->atualizacampos();
     if($this->cm09_i_sepultamento == null ){
       $this->erro_sql = " Campo Código Sepultamento nao Informado.";
       $this->erro_campo = "cm09_i_sepultamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm09_d_emissao == null ){
       $this->erro_sql = " Campo Emissão nao Informado.";
       $this->erro_campo = "cm09_d_emissao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($cm09_i_codigo == "" || $cm09_i_codigo == null ){
       $result = @db_query("select nextval('notaserv_cm09_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: notaserv_cm09_i_codigo_seq do campo: cm09_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->cm09_i_codigo = pg_result($result,0,0);
     }else{
       $result = @db_query("select last_value from notaserv_cm09_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $cm09_i_codigo)){
         $this->erro_sql = " Campo cm09_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cm09_i_codigo = $cm09_i_codigo;
       }
     }
     if(($this->cm09_i_codigo == null) || ($this->cm09_i_codigo == "") ){
       $this->erro_sql = " Campo cm09_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into notaserv(
                                       cm09_i_codigo
                                      ,cm09_i_sepultamento
                                      ,cm09_d_emissao
                       )
                values (
                                $this->cm09_i_codigo
                               ,$this->cm09_i_sepultamento
                               ,".($this->cm09_d_emissao == "null" || $this->cm09_d_emissao == ""?"null":"'".$this->cm09_d_emissao."'")."
                      )";
     $result = @db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "notaserv ($this->cm09_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "notaserv já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "notaserv ($this->cm09_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm09_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cm09_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountkey values($acount,1000100,'$this->cm09_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1000022,1000100,'','".AddSlashes(pg_result($resaco,0,'cm09_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1000022,1000102,'','".AddSlashes(pg_result($resaco,0,'cm09_i_sepultamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1000022,1000103,'','".AddSlashes(pg_result($resaco,0,'cm09_d_emissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($cm09_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update notaserv set ";
     $virgula = "";
     if(trim($this->cm09_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm09_i_codigo"])){
       $sql  .= $virgula." cm09_i_codigo = $this->cm09_i_codigo ";
       $virgula = ",";
       if(trim($this->cm09_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "cm09_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm09_i_sepultamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm09_i_sepultamento"])){
       $sql  .= $virgula." cm09_i_sepultamento = $this->cm09_i_sepultamento ";
       $virgula = ",";
       if(trim($this->cm09_i_sepultamento) == null ){
         $this->erro_sql = " Campo Código Sepultamento nao Informado.";
         $this->erro_campo = "cm09_i_sepultamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm09_d_emissao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm09_d_emissao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cm09_d_emissao_dia"] !="") ){
       $sql  .= $virgula." cm09_d_emissao = '$this->cm09_d_emissao' ";
       $virgula = ",";
       if(trim($this->cm09_d_emissao) == null ){
         $this->erro_sql = " Campo Emissão nao Informado.";
         $this->erro_campo = "cm09_d_emissao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["cm09_d_emissao_dia"])){
         $sql  .= $virgula." cm09_d_emissao = null ";
         $virgula = ",";
         if(trim($this->cm09_d_emissao) == null ){
           $this->erro_sql = " Campo Emissão nao Informado.";
           $this->erro_campo = "cm09_d_emissao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($cm09_i_codigo!=null){
       $sql .= " cm09_i_codigo = $this->cm09_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cm09_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountkey values($acount,1000100,'$this->cm09_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm09_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1000022,1000100,'".AddSlashes(pg_result($resaco,$conresaco,'cm09_i_codigo'))."','$this->cm09_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm09_i_sepultamento"]))
           $resac = db_query("insert into db_acount values($acount,1000022,1000102,'".AddSlashes(pg_result($resaco,$conresaco,'cm09_i_sepultamento'))."','$this->cm09_i_sepultamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm09_d_emissao"]))
           $resac = db_query("insert into db_acount values($acount,1000022,1000103,'".AddSlashes(pg_result($resaco,$conresaco,'cm09_d_emissao'))."','$this->cm09_d_emissao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "notaserv nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm09_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "notaserv nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm09_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm09_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($cm09_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cm09_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountkey values($acount,1000100,'$this->cm09_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1000022,1000100,'','".AddSlashes(pg_result($resaco,$iresaco,'cm09_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1000022,1000102,'','".AddSlashes(pg_result($resaco,$iresaco,'cm09_i_sepultamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1000022,1000103,'','".AddSlashes(pg_result($resaco,$iresaco,'cm09_d_emissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from notaserv
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cm09_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cm09_i_codigo = $cm09_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "notaserv nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cm09_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "notaserv nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cm09_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cm09_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:notaserv";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $cm09_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from notaserv ";
     $sql .= "      inner join sepultamentos  on  sepultamentos.cm01_i_codigo = notaserv.cm09_i_sepultamento";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = sepultamentos.cm01_i_codigo";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = sepultamentos.cm01_i_funcionario";
     $sql .= "      left  join medicos  on  medicos.sd03_i_codigo = sepultamentos.cm01_i_medico";
     $sql .= "      inner join causa  on  causa.cm04_i_codigo = sepultamentos.cm01_i_causa";
     $sql .= "      inner join cemiterio  on  cemiterio.cm14_i_codigo = sepultamentos.cm01_i_cemiterio";
     $sql .= "      left  join hospitais  on  hospitais.cm18_i_hospital = sepultamentos.cm01_i_hospital";
     $sql .= "      left  join funerarias  on  funerarias.cm17_i_funeraria = sepultamentos.cm01_i_funeraria";
     $sql2 = "";
     if($dbwhere==""){
       if($cm09_i_codigo!=null ){
         $sql2 .= " where notaserv.cm09_i_codigo = $cm09_i_codigo ";
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
   function sql_query_file ( $cm09_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from notaserv ";
     $sql2 = "";
     if($dbwhere==""){
       if($cm09_i_codigo!=null ){
         $sql2 .= " where notaserv.cm09_i_codigo = $cm09_i_codigo ";
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
