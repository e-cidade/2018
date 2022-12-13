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

//MODULO: contabilidade
//CLASSE DA ENTIDADE conlancamrec
class cl_conlancamrec {
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
   var $c74_codlan = 0;
   var $c74_anousu = 0;
   var $c74_codrec = 0;
   var $c74_data_dia = null;
   var $c74_data_mes = null;
   var $c74_data_ano = null;
   var $c74_data = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 c74_codlan = int4 = Código Lançamento
                 c74_anousu = int4 = Exercício
                 c74_codrec = int4 = Receita
                 c74_data = date = Data
                 ";
   //funcao construtor da classe
   function cl_conlancamrec() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conlancamrec");
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
       $this->c74_codlan = ($this->c74_codlan == ""?@$GLOBALS["HTTP_POST_VARS"]["c74_codlan"]:$this->c74_codlan);
       $this->c74_anousu = ($this->c74_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c74_anousu"]:$this->c74_anousu);
       $this->c74_codrec = ($this->c74_codrec == ""?@$GLOBALS["HTTP_POST_VARS"]["c74_codrec"]:$this->c74_codrec);
       if($this->c74_data == ""){
         $this->c74_data_dia = ($this->c74_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c74_data_dia"]:$this->c74_data_dia);
         $this->c74_data_mes = ($this->c74_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c74_data_mes"]:$this->c74_data_mes);
         $this->c74_data_ano = ($this->c74_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c74_data_ano"]:$this->c74_data_ano);
         if($this->c74_data_dia != ""){
            $this->c74_data = $this->c74_data_ano."-".$this->c74_data_mes."-".$this->c74_data_dia;
         }
       }
     }else{
       $this->c74_codlan = ($this->c74_codlan == ""?@$GLOBALS["HTTP_POST_VARS"]["c74_codlan"]:$this->c74_codlan);
     }
   }
   // funcao para inclusao
   function incluir ($c74_codlan){
      $this->atualizacampos();
     if($this->c74_anousu == null ){
       $this->erro_sql = " Campo Exercício nao Informado.";
       $this->erro_campo = "c74_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c74_codrec == null ){
       $this->erro_sql = " Campo Receita nao Informado.";
       $this->erro_campo = "c74_codrec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c74_data == null ){
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "c74_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->c74_codlan = $c74_codlan;
     if(($this->c74_codlan == null) || ($this->c74_codlan == "") ){
       $this->erro_sql = " Campo c74_codlan nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conlancamrec(
                                       c74_codlan
                                      ,c74_anousu
                                      ,c74_codrec
                                      ,c74_data
                       )
                values (
                                $this->c74_codlan
                               ,$this->c74_anousu
                               ,$this->c74_codrec
                               ,".($this->c74_data == "null" || $this->c74_data == ""?"null":"'".$this->c74_data."'")."
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Receita do Lançamento ($this->c74_codlan) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Receita do Lançamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Receita do Lançamento ($this->c74_codlan) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c74_codlan;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       $resaco = $this->sql_record($this->sql_query_file($this->c74_codlan));
       if (($resaco!=false)||($this->numrows!=0)) {

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5204,'$this->c74_codlan','I')");
         $resac = db_query("insert into db_acount values($acount,767,5204,'','".AddSlashes(pg_result($resaco,0,'c74_codlan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,767,5205,'','".AddSlashes(pg_result($resaco,0,'c74_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,767,5206,'','".AddSlashes(pg_result($resaco,0,'c74_codrec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,767,5904,'','".AddSlashes(pg_result($resaco,0,'c74_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($c74_codlan=null) {
      $this->atualizacampos();
     $sql = " update conlancamrec set ";
     $virgula = "";
     if(trim($this->c74_codlan)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c74_codlan"])){
       $sql  .= $virgula." c74_codlan = $this->c74_codlan ";
       $virgula = ",";
       if(trim($this->c74_codlan) == null ){
         $this->erro_sql = " Campo Código Lançamento nao Informado.";
         $this->erro_campo = "c74_codlan";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c74_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c74_anousu"])){
       $sql  .= $virgula." c74_anousu = $this->c74_anousu ";
       $virgula = ",";
       if(trim($this->c74_anousu) == null ){
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "c74_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c74_codrec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c74_codrec"])){
       $sql  .= $virgula." c74_codrec = $this->c74_codrec ";
       $virgula = ",";
       if(trim($this->c74_codrec) == null ){
         $this->erro_sql = " Campo Receita nao Informado.";
         $this->erro_campo = "c74_codrec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c74_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c74_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c74_data_dia"] !="") ){
       $sql  .= $virgula." c74_data = '$this->c74_data' ";
       $virgula = ",";
       if(trim($this->c74_data) == null ){
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "c74_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["c74_data_dia"])){
         $sql  .= $virgula." c74_data = null ";
         $virgula = ",";
         if(trim($this->c74_data) == null ){
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "c74_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($c74_codlan!=null){
       $sql .= " c74_codlan = $this->c74_codlan";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       $resaco = $this->sql_record($this->sql_query_file($this->c74_codlan));
       if($this->numrows>0){
         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,5204,'$this->c74_codlan','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c74_codlan"]))
             $resac = db_query("insert into db_acount values($acount,767,5204,'".AddSlashes(pg_result($resaco,$conresaco,'c74_codlan'))."','$this->c74_codlan',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c74_anousu"]))
             $resac = db_query("insert into db_acount values($acount,767,5205,'".AddSlashes(pg_result($resaco,$conresaco,'c74_anousu'))."','$this->c74_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c74_codrec"]))
             $resac = db_query("insert into db_acount values($acount,767,5206,'".AddSlashes(pg_result($resaco,$conresaco,'c74_codrec'))."','$this->c74_codrec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c74_data"]))
             $resac = db_query("insert into db_acount values($acount,767,5904,'".AddSlashes(pg_result($resaco,$conresaco,'c74_data'))."','$this->c74_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Receita do Lançamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c74_codlan;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Receita do Lançamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c74_codlan;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c74_codlan;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($c74_codlan=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       if($dbwhere==null || $dbwhere==""){
         $resaco = $this->sql_record($this->sql_query_file($c74_codlan));
       }else{
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if(($resaco!=false)||($this->numrows!=0)){
         for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,5204,'$c74_codlan','E')");
           $resac = db_query("insert into db_acount values($acount,767,5204,'','".AddSlashes(pg_result($resaco,$iresaco,'c74_codlan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,767,5205,'','".AddSlashes(pg_result($resaco,$iresaco,'c74_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,767,5206,'','".AddSlashes(pg_result($resaco,$iresaco,'c74_codrec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,767,5904,'','".AddSlashes(pg_result($resaco,$iresaco,'c74_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from conlancamrec
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c74_codlan != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c74_codlan = $c74_codlan ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Receita do Lançamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c74_codlan;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Receita do Lançamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c74_codlan;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c74_codlan;
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
        $this->erro_sql   = "Record Vazio na Tabela:conlancamrec";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $c74_codlan=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conlancamrec ";
     $sql .= "      inner join conlancam  on  conlancam.c70_codlan = conlancamrec.c74_codlan";
     $sql .= "      inner join orcreceita  on  orcreceita.o70_anousu = conlancamrec.c74_anousu and  orcreceita.o70_codrec = conlancamrec.c74_codrec";
     $sql .= "      inner join db_config  on  db_config.codigo = orcreceita.o70_instit";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcreceita.o70_codigo";
     $sql .= "      inner join orcfontes  on  orcfontes.o57_codfon = orcreceita.o70_codfon and orcfontes.o57_codfon = orcreceita.o70_anousu ";
     $sql .= "      inner join db_config  as a on   a.codigo = orcreceita.o70_instit";
     $sql .= "      inner join orctiporec  as b on   b.o15_codigo = orcreceita.o70_codigo";
     $sql .= "      inner join orcfontes  as c on   c.o57_codfon = orcreceita.o70_codfon and c.o57_anousu = orcreceita.o70_anousu ";
     $sql2 = "";
     if($dbwhere==""){
       if($c74_codlan!=null ){
         $sql2 .= " where conlancamrec.c74_codlan = $c74_codlan ";
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
   function sql_query_file ( $c74_codlan=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conlancamrec ";
     $sql2 = "";
     if($dbwhere==""){
       if($c74_codlan!=null ){
         $sql2 .= " where conlancamrec.c74_codlan = $c74_codlan ";
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

  function sql_query_conPlanoCodDoc ($c74_codlan=null,$campos="*",$ordem=null,$dbwhere=""){
    $sql = "select ";
    if($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++) {

        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }else {
      $sql .= $campos;
    }
    $sql .= " from conlancamrec 																															  ";
    $sql .= "      inner join conlancam    on  conlancam.c70_codlan   = conlancamrec.c74_codlan	";
    $sql .= "      inner join conlancamdoc on conlancamdoc.c71_codlan = conlancamrec.c74_codlan ";
    $sql .= "      inner join orcreceita   on  orcreceita.o70_anousu  = conlancamrec.c74_anousu ";
    $sql .= "      												and  orcreceita.o70_codrec  = conlancamrec.c74_codrec ";
    $sql .= "      inner join db_config    on  db_config.codigo       = orcreceita.o70_instit   ";
    $sql .= "      inner join orctiporec   on  orctiporec.o15_codigo  = orcreceita.o70_codigo   ";
    $sql .= "      inner join orcfontes    on  orcfontes.o57_codfon   = orcreceita.o70_codfon   ";
    $sql .= "      												and orcfontes.o57_anousu    = orcreceita.o70_anousu      ";
    $sql2 = "";
    if($dbwhere=="") {

      if($c74_codlan!=null ) {
        $sql2 .= " where conlancamrec.c74_codlan = $c74_codlan ";
      }
    } else if($dbwhere != "") {

      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ) {

      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++) {

        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }


  function sql_query_dados_receita ($c74_codlan=null,$campos="*",$ordem=null,$dbwhere=""){
    $sql = "select ";
    if($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++) {

        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }else {
      $sql .= $campos;
    }
    $sql .= " from conlancamrec 																															  ";
    $sql .= "      inner join orcreceita   on  orcreceita.o70_anousu  = conlancamrec.c74_anousu ";
    $sql .= "      												and  orcreceita.o70_codrec  = conlancamrec.c74_codrec ";
    $sql .= "      inner join orctiporec   on  orctiporec.o15_codigo  = orcreceita.o70_codigo   ";
    $sql .= "      inner join orcfontes    on  orcfontes.o57_codfon   = orcreceita.o70_codfon   ";
    $sql .= "      												and orcfontes.o57_anousu    = orcreceita.o70_anousu      ";
    $sql2 = "";
    if($dbwhere=="") {

      if($c74_codlan!=null ) {
        $sql2 .= " where conlancamrec.c74_codlan = $c74_codlan ";
      }
    } else if($dbwhere != "") {

      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ) {

      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++) {

        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }


}
?>