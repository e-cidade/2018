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

//MODULO: Caixa
//CLASSE DA ENTIDADE tabrec
class cl_tabrec {
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
   var $k02_codigo = 0;
   var $k02_tipo = null;
   var $k02_descr = null;
   var $k02_drecei = null;
   var $k02_codjm = 0;
   var $k02_recjur = 0;
   var $k02_recmul = 0;
   var $k02_limite_dia = null;
   var $k02_limite_mes = null;
   var $k02_limite_ano = null;
   var $k02_limite = null;
   var $k02_tabrectipo = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 k02_codigo = int4 = Receita
                 k02_tipo = char(1) = tipo da receita
                 k02_descr = char(15) = Descrição Receita Tesouraria
                 k02_drecei = varchar(40) = Descrição Completa Receita Tesouraria
                 k02_codjm = int4 = codigo do juro e multa
                 k02_recjur = int4 = Receita Juros
                 k02_recmul = int4 = Receita Multa
                 k02_limite = date = Data limite
                 k02_tabrectipo = int4 = Cadastro de Tipo de Receita
                 ";
   //funcao construtor da classe
   function cl_tabrec() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tabrec");
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
       $this->k02_codigo = ($this->k02_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_codigo"]:$this->k02_codigo);
       $this->k02_tipo = ($this->k02_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_tipo"]:$this->k02_tipo);
       $this->k02_descr = ($this->k02_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_descr"]:$this->k02_descr);
       $this->k02_drecei = ($this->k02_drecei == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_drecei"]:$this->k02_drecei);
       $this->k02_codjm = ($this->k02_codjm == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_codjm"]:$this->k02_codjm);
       $this->k02_recjur = ($this->k02_recjur == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_recjur"]:$this->k02_recjur);
       $this->k02_recmul = ($this->k02_recmul == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_recmul"]:$this->k02_recmul);
       if($this->k02_limite == ""){
         $this->k02_limite_dia = ($this->k02_limite_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_limite_dia"]:$this->k02_limite_dia);
         $this->k02_limite_mes = ($this->k02_limite_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_limite_mes"]:$this->k02_limite_mes);
         $this->k02_limite_ano = ($this->k02_limite_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_limite_ano"]:$this->k02_limite_ano);
         if($this->k02_limite_dia != ""){
            $this->k02_limite = $this->k02_limite_ano."-".$this->k02_limite_mes."-".$this->k02_limite_dia;
         }
       }
       $this->k02_tabrectipo = ($this->k02_tabrectipo == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_tabrectipo"]:$this->k02_tabrectipo);
     }else{
       $this->k02_codigo = ($this->k02_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_codigo"]:$this->k02_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($k02_codigo){
      $this->atualizacampos();
     if($this->k02_tipo == null ){
       $this->erro_sql = " Campo tipo da receita nao Informado.";
       $this->erro_campo = "k02_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k02_descr == null ){
       $this->erro_sql = " Campo Descrição Receita Tesouraria nao Informado.";
       $this->erro_campo = "k02_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k02_drecei == null ){
       $this->erro_sql = " Campo Descrição Completa Receita Tesouraria nao Informado.";
       $this->erro_campo = "k02_drecei";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k02_codjm == null ){
       $this->erro_sql = " Campo codigo do juro e multa nao Informado.";
       $this->erro_campo = "k02_codjm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k02_recjur == null ){
       $this->k02_recjur = "0";
     }
     if($this->k02_recmul == null ){
       $this->k02_recmul = "0";
     }
     if($this->k02_limite == null ){
       $this->k02_limite = "null";
     }
     if($this->k02_tabrectipo == null ){
       $this->erro_sql = " Campo Cadastro de Tipo de Receita nao Informado.";
       $this->erro_campo = "k02_tabrectipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k02_codigo == "" || $k02_codigo == null ){
       $result = db_query("select nextval('tabrec_k02_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tabrec_k02_codigo_seq do campo: k02_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->k02_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from tabrec_k02_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $k02_codigo)){
         $this->erro_sql = " Campo k02_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k02_codigo = $k02_codigo;
       }
     }
     if(($this->k02_codigo == null) || ($this->k02_codigo == "") ){
       $this->erro_sql = " Campo k02_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tabrec(
                                       k02_codigo
                                      ,k02_tipo
                                      ,k02_descr
                                      ,k02_drecei
                                      ,k02_codjm
                                      ,k02_recjur
                                      ,k02_recmul
                                      ,k02_limite
                                      ,k02_tabrectipo
                       )
                values (
                                $this->k02_codigo
                               ,'$this->k02_tipo'
                               ,'$this->k02_descr'
                               ,'$this->k02_drecei'
                               ,$this->k02_codjm
                               ,$this->k02_recjur
                               ,$this->k02_recmul
                               ,".($this->k02_limite == "null" || $this->k02_limite == ""?"null":"'".$this->k02_limite."'")."
                               ,$this->k02_tabrectipo
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Receitas da Tasouraria ($this->k02_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Receitas da Tasouraria já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Receitas da Tasouraria ($this->k02_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k02_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k02_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,382,'$this->k02_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,75,382,'','".AddSlashes(pg_result($resaco,0,'k02_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,75,383,'','".AddSlashes(pg_result($resaco,0,'k02_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,75,384,'','".AddSlashes(pg_result($resaco,0,'k02_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,75,385,'','".AddSlashes(pg_result($resaco,0,'k02_drecei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,75,386,'','".AddSlashes(pg_result($resaco,0,'k02_codjm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,75,425,'','".AddSlashes(pg_result($resaco,0,'k02_recjur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,75,426,'','".AddSlashes(pg_result($resaco,0,'k02_recmul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,75,6372,'','".AddSlashes(pg_result($resaco,0,'k02_limite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,75,15194,'','".AddSlashes(pg_result($resaco,0,'k02_tabrectipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($k02_codigo=null) {
      $this->atualizacampos();
     $sql = " update tabrec set ";
     $virgula = "";
     if(trim($this->k02_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_codigo"])){
       $sql  .= $virgula." k02_codigo = $this->k02_codigo ";
       $virgula = ",";
       if(trim($this->k02_codigo) == null ){
         $this->erro_sql = " Campo Receita nao Informado.";
         $this->erro_campo = "k02_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k02_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_tipo"])){
       $sql  .= $virgula." k02_tipo = '$this->k02_tipo' ";
       $virgula = ",";
       if(trim($this->k02_tipo) == null ){
         $this->erro_sql = " Campo tipo da receita nao Informado.";
         $this->erro_campo = "k02_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k02_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_descr"])){
       $sql  .= $virgula." k02_descr = '$this->k02_descr' ";
       $virgula = ",";
       if(trim($this->k02_descr) == null ){
         $this->erro_sql = " Campo Descrição Receita Tesouraria nao Informado.";
         $this->erro_campo = "k02_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k02_drecei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_drecei"])){
       $sql  .= $virgula." k02_drecei = '$this->k02_drecei' ";
       $virgula = ",";
       if(trim($this->k02_drecei) == null ){
         $this->erro_sql = " Campo Descrição Completa Receita Tesouraria nao Informado.";
         $this->erro_campo = "k02_drecei";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k02_codjm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_codjm"])){
       $sql  .= $virgula." k02_codjm = $this->k02_codjm ";
       $virgula = ",";
       if(trim($this->k02_codjm) == null ){
         $this->erro_sql = " Campo codigo do juro e multa nao Informado.";
         $this->erro_campo = "k02_codjm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k02_recjur)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_recjur"])){
        if(trim($this->k02_recjur)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k02_recjur"])){
           $this->k02_recjur = "0" ;
        }
       $sql  .= $virgula." k02_recjur = $this->k02_recjur ";
       $virgula = ",";
     }
     if(trim($this->k02_recmul)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_recmul"])){
        if(trim($this->k02_recmul)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k02_recmul"])){
           $this->k02_recmul = "0" ;
        }
       $sql  .= $virgula." k02_recmul = $this->k02_recmul ";
       $virgula = ",";
     }
     if(trim($this->k02_limite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_limite_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k02_limite_dia"] !="") ){
       $sql  .= $virgula." k02_limite = '$this->k02_limite' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["k02_limite_dia"])){
         $sql  .= $virgula." k02_limite = null ";
         $virgula = ",";
       }
     }
     if(trim($this->k02_tabrectipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_tabrectipo"])){
       $sql  .= $virgula." k02_tabrectipo = $this->k02_tabrectipo ";
       $virgula = ",";
       if(trim($this->k02_tabrectipo) == null ){
         $this->erro_sql = " Campo Cadastro de Tipo de Receita nao Informado.";
         $this->erro_campo = "k02_tabrectipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k02_codigo!=null){
       $sql .= " k02_codigo = $this->k02_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k02_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,382,'$this->k02_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_codigo"]) || $this->k02_codigo != "")
           $resac = db_query("insert into db_acount values($acount,75,382,'".AddSlashes(pg_result($resaco,$conresaco,'k02_codigo'))."','$this->k02_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_tipo"]) || $this->k02_tipo != "")
           $resac = db_query("insert into db_acount values($acount,75,383,'".AddSlashes(pg_result($resaco,$conresaco,'k02_tipo'))."','$this->k02_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_descr"]) || $this->k02_descr != "")
           $resac = db_query("insert into db_acount values($acount,75,384,'".AddSlashes(pg_result($resaco,$conresaco,'k02_descr'))."','$this->k02_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_drecei"]) || $this->k02_drecei != "")
           $resac = db_query("insert into db_acount values($acount,75,385,'".AddSlashes(pg_result($resaco,$conresaco,'k02_drecei'))."','$this->k02_drecei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_codjm"]) || $this->k02_codjm != "")
           $resac = db_query("insert into db_acount values($acount,75,386,'".AddSlashes(pg_result($resaco,$conresaco,'k02_codjm'))."','$this->k02_codjm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_recjur"]) || $this->k02_recjur != "")
           $resac = db_query("insert into db_acount values($acount,75,425,'".AddSlashes(pg_result($resaco,$conresaco,'k02_recjur'))."','$this->k02_recjur',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_recmul"]) || $this->k02_recmul != "")
           $resac = db_query("insert into db_acount values($acount,75,426,'".AddSlashes(pg_result($resaco,$conresaco,'k02_recmul'))."','$this->k02_recmul',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_limite"]) || $this->k02_limite != "")
           $resac = db_query("insert into db_acount values($acount,75,6372,'".AddSlashes(pg_result($resaco,$conresaco,'k02_limite'))."','$this->k02_limite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k02_tabrectipo"]) || $this->k02_tabrectipo != "")
           $resac = db_query("insert into db_acount values($acount,75,15194,'".AddSlashes(pg_result($resaco,$conresaco,'k02_tabrectipo'))."','$this->k02_tabrectipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Receitas da Tasouraria nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k02_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Receitas da Tasouraria nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k02_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k02_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($k02_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k02_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,382,'$k02_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,75,382,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,75,383,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,75,384,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,75,385,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_drecei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,75,386,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_codjm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,75,425,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_recjur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,75,426,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_recmul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,75,6372,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_limite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,75,15194,'','".AddSlashes(pg_result($resaco,$iresaco,'k02_tabrectipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tabrec
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k02_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k02_codigo = $k02_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Receitas da Tasouraria nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k02_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Receitas da Tasouraria nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k02_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k02_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:tabrec";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function atualiza_sequencia(){
    $sql =  "select nextval('tabrec_k02_codigo_seq') as k02_codigo";
    return $sql;
  }
   function sql_query ( $k02_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from tabrec ";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql .= "      inner join tabrectipo  on  tabrectipo.k116_sequencial = tabrec.k02_tabrectipo";
     $sql .= "      inner join inflan  on  inflan.i01_codigo = tabrecjm.k02_corr";
     $sql2 = "";
     if($dbwhere==""){
       if($k02_codigo!=null ){
         $sql2 .= " where tabrec.k02_codigo = $k02_codigo ";
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
   function sql_query_cadastrar ( $k02_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from ( select k02_estorc,tabrec.*,c60_codcon, c60_descr";
     $sql .= "        from tabrec ";
     $sql .= "             inner join taborc on tabrec.k02_codigo = taborc.k02_codigo ";
     $sql .= "             inner join orcreceita on taborc.k02_codrec = orcreceita.o70_codrec and
                                                    orcreceita.o70_anousu = taborc.k02_anousu and
						    orcreceita.o70_instit = ".db_getsession("DB_instit");
     if (USE_PCASP) {
       $sql .= "  inner join conplanoorcamento on c60_codcon = orcreceita.o70_codfon and c60_anousu=o70_anousu   ";
     } else {
       $sql .= " inner join conplano on c60_codcon = orcreceita.o70_codfon and c60_anousu=o70_anousu   ";
     }
     $sql .= "        where taborc.k02_anousu = ".db_getsession("DB_anousu");
     $sql .= "        union";
     $sql .= "        select k02_estpla,tabrec.*,c60_codcon, c60_descr ";
     $sql .= "        from tabrec ";
     $sql .= "             inner join tabplan on tabrec.k02_codigo = tabplan.k02_codigo ";
     $sql .= "             inner join conplanoreduz on conplanoreduz.c61_anousu=tabplan.k02_anousu and
                                                       conplanoreduz.c61_reduz = tabplan.k02_reduz and
						       conplanoreduz.c61_instit = ".db_getsession("DB_instit");
     $sql .= "             inner join conplano on c60_codcon = c61_codcon and c60_anousu=c61_anousu   ";

     $sql .= "        where tabplan.k02_anousu = ".db_getsession("DB_anousu");
     $sql .= "      ) as tabrec";
     $sql2 = "";
     if($dbwhere==""){
      if($k02_codigo!=null ){
         $sql2 .= " where k02_codigo = $k02_codigo ";
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
   function sql_query_file ( $k02_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from tabrec ";
     $sql2 = "";
     if($dbwhere==""){
       if($k02_codigo!=null ){
         $sql2 .= " where tabrec.k02_codigo = $k02_codigo ";
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
   function sql_query_inst ( $k02_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from ( select k02_estorc,tabrec.k02_codigo, tabrec.k02_tipo, tabrec.k02_descr, tabrec.k02_drecei,  tabrec.k02_codjm, tabrec.k02_recjur, tabrec.k02_recmul, tabrec.k02_limite, o70_codrec,0 as c61_reduz,o70_codigo as recurso ";
     $sql .= "        from tabrec ";
     $sql .= "             inner join taborc on tabrec.k02_codigo = taborc.k02_codigo and taborc.k02_anousu = ".db_getsession("DB_anousu");
     $sql .= "             inner join orcreceita on taborc.k02_codrec = orcreceita.o70_codrec and orcreceita.o70_anousu = ".db_getsession("DB_anousu")." and orcreceita.o70_instit = ".db_getsession("DB_instit");
     if (USE_PCASP) {

       $sql .= "  inner join conplanoorcamento on orcreceita.o70_anousu = conplanoorcamento.c60_anousu ";
       $sql .= "                              and orcreceita.o70_codfon = conplanoorcamento.c60_codcon ";
     } else {
       $sql .= "  inner join conplano on orcreceita.o70_anousu=conplano.c60_anousu and orcreceita.o70_codfon = conplano.c60_codcon ";
     }
     $sql .= "        where k02_anousu = ".db_getsession("DB_anousu");
     $sql .= "        union";
     $sql .= "        select k02_estpla, tabrec.k02_codigo, tabrec.k02_tipo, tabrec.k02_descr, tabrec.k02_drecei, tabrec.k02_codjm, tabrec.k02_recjur, tabrec.k02_recmul, tabrec.k02_limite,0,c61_reduz,c61_codigo  ";
     $sql .= "        from tabrec ";
     $sql .= "             inner join tabplan on tabrec.k02_codigo = tabplan.k02_codigo and tabplan.k02_anousu = ".db_getsession("DB_anousu");
     $sql .= "             inner join conplanoreduz on   conplanoreduz.c61_anousu=tabplan.k02_anousu and conplanoreduz.c61_reduz = tabplan.k02_reduz and conplanoreduz.c61_instit = ".db_getsession("DB_instit");
     $sql .= "             inner join conplano on conplanoreduz.c61_anousu=conplano.c60_anousu and conplanoreduz.c61_codcon = conplano.c60_codcon ";
     $sql .= "        where k02_anousu = ".db_getsession("DB_anousu");
     $sql .= "      ) as tabrec";
     $sql2 = "";
     if($dbwhere==""){
      if($k02_codigo!=null ){
         $sql2 .= " where k02_codigo = $k02_codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql2 .= ($sql2!=""?" and ":" where ") . " (k02_limite is null or k02_limite >= '" . date('Y-m-d',db_getsession("DB_datausu")) . "')";
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
   function sql_query_inst_cadastrar ( $k02_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from ( select k02_estorc,tabrec.* ";
     $sql .= "        from tabrec ";
     $sql .= "             left join taborc on tabrec.k02_codigo = taborc.k02_codigo and taborc.k02_anousu = ".db_getsession("DB_anousu");
     $sql .= "             left join orcreceita on taborc.k02_codrec = orcreceita.o70_codrec and orcreceita.o70_anousu = ".db_getsession("DB_anousu")." and orcreceita.o70_instit = ".db_getsession("DB_instit");
     $sql .= "        where k02_anousu = ".db_getsession("DB_anousu");
     $sql .= "        union";
     $sql .= "        select k02_estpla,tabrec.*  ";
     $sql .= "        from tabrec ";
     $sql .= "             left join tabplan on tabrec.k02_codigo = tabplan.k02_codigo and tabplan.k02_anousu = ".db_getsession("DB_anousu");
     $sql .= "             left join conplanoreduz on   conplanoreduz.c61_anousu=tabplan.k02_anousu and conplanoreduz.c61_reduz = tabplan.k02_reduz and conplanoreduz.c61_instit = ".db_getsession("DB_instit");
     $sql .= "        where k02_anousu = ".db_getsession("DB_anousu")." ";
     $sql .= "      ) as tabrec";
     $sql2 = "";
     if($dbwhere==""){
      if($k02_codigo!=null ){
         $sql2 .= " where k02_codigo = $k02_codigo ";
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
   function sql_query_inst_depto ( $k02_codigo=null,$campos="*",$ordem=null,$dbwhere="", $lRelatorio=null){
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
     $sql .= " from ( select k02_estorc,tabrec.*,o70_codrec,0 as c61_reduz,o70_codigo as recurso, " ;
		 $sql .= "               case when k79_arretipo is not null then k79_arretipo ";
     $sql .= "                    else (select k03_reciboprot from numpref where k03_anousu = ".db_getsession("DB_anousu")." and k03_instit = ".db_getsession("DB_instit")." )";
		 $sql .= "               end as arretipo ";
		 $sql .= "        from tabrec ";
     $sql .= "             inner join taborc        on tabrec.k02_codigo      = taborc.k02_codigo and taborc.k02_anousu = ".db_getsession("DB_anousu");
     $sql .= "             inner join orcreceita    on taborc.k02_codrec      = orcreceita.o70_codrec and orcreceita.o70_anousu = ".db_getsession("DB_anousu")." and orcreceita.o70_instit = ".db_getsession("DB_instit");
		 $sql .= "             left join tabrecdepto    on tabrecdepto.k98_receit = tabrec.k02_codigo ";
		 $sql .= "             left join tabrecarretipo on k79_receit             = tabrec.k02_codigo ";
	   $sql .= "        where k02_anousu = ".db_getsession("DB_anousu")." and (k98_coddepto = ".db_getsession("DB_coddepto")." or k98_receit is null ) ";
     $sql .= "        union";
     $sql .= "        select k02_estpla ,tabrec.*,0,c61_reduz,c61_codigo,  ";
     $sql .= "               case when k79_arretipo is not null then k79_arretipo ";
     $sql .= "                    else (select k03_reciboprot from numpref where k03_anousu= ".db_getsession("DB_anousu")." and k03_instit = ".db_getsession("DB_instit")." )";
 		 $sql .= "               end as arretipo ";
     $sql .= "        from tabrec ";
     $sql .= "             inner join tabplan       on tabrec.k02_codigo         = tabplan.k02_codigo and tabplan.k02_anousu = ".db_getsession("DB_anousu");
     $sql .= "             inner join conplanoreduz on   conplanoreduz.c61_anousu= tabplan.k02_anousu and conplanoreduz.c61_reduz = tabplan.k02_reduz and conplanoreduz.c61_instit = ".db_getsession("DB_instit");
 		 $sql .= "             left join tabrecdepto    on tabrecdepto.k98_receit    = tabrec.k02_codigo ";
		 $sql .= "             left join tabrecarretipo on k79_receit                = tabrec.k02_codigo ";
     $sql .= "        where k02_anousu = ".db_getsession("DB_anousu")." and (k98_coddepto = ".db_getsession("DB_coddepto")." or k98_receit is null ) ";
     $sql .= "      ) as tabrec";
		 $sql .= " left join arretipo on tabrec.arretipo = arretipo.k00_tipo ";
     $sql2 = "";
     if($dbwhere==""){
      if($k02_codigo!=null ){
         $sql2 .= " where k02_codigo = $k02_codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     if ($lRelatorio != null || $lRelatorio == 1) {
     	$sql2 .= "";//($sql2!=""?" and ":" where ") . " (k02_limite is null or k02_limite >= '" . date('Y-m-d',db_getsession("DB_datausu")) . "')";
     } else {
       $sql2 .= ($sql2!=""?" and ":" where ") . " (k02_limite is null or k02_limite >= '" . date('Y-m-d',db_getsession("DB_datausu")) . "')";
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
   function sql_query_inst_taxa ( $k07_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
      // k07_codigo = codigo da receita da subtaxa
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
     $sql .= " from ( select tabdesc.*,k02_estorc,tabrec.*,o70_codrec,0 as c61_reduz,o70_codigo as recurso, ";
     $sql .= " case when k78_arretipo is not null then k78_arretipo ";
     $sql .= "      when k79_arretipo is not null then k79_arretipo ";
     $sql .= "      else (select k03_reciboprot from numpref where k03_anousu= ".db_getsession("DB_anousu")." and k03_instit = ".db_getsession("DB_instit")." )";
		 $sql .= " end as arretipo ";
     $sql .= "        from tabrec ";
     $sql .= "             inner join tabdesc on tabrec.k02_codigo = tabdesc.k07_codigo and k07_instit        = ".db_getsession("DB_instit");
     $sql .= "             inner join taborc  on tabrec.k02_codigo = taborc.k02_codigo  and taborc.k02_anousu = ".db_getsession("DB_anousu");
     $sql .= "             inner join orcreceita on taborc.k02_codrec = orcreceita.o70_codrec and orcreceita.o70_anousu = ".db_getsession("DB_anousu")." and orcreceita.o70_instit = ".db_getsession("DB_instit");
		 $sql .= "             left join tabdescarretipo on k78_tabdesc  = tabdesc.codsubrec  ";
     $sql .= "             left join tabrecarretipo  on k79_receit   = tabrec.k02_codigo       ";
     $sql .= "        where k02_anousu = ".db_getsession("DB_anousu");
     $sql .= "        union";
     $sql .= "        select tabdesc.*,k02_estpla ,tabrec.*,0,c61_reduz,c61_codigo , ";
     $sql .= " case when k78_arretipo is not null then k78_arretipo ";
     $sql .= "      when k79_arretipo is not null then k79_arretipo ";
     $sql .= "      else (select k03_reciboprot from numpref where k03_anousu= ".db_getsession("DB_anousu")." and k03_instit = ".db_getsession("DB_instit")." )";
		 $sql .= " end as arretipo ";
     $sql .= "        from tabrec ";
     $sql .= "             inner join tabdesc on tabrec.k02_codigo = tabdesc.k07_codigo and k07_instit         = ".db_getsession("DB_instit");
     $sql .= "             inner join tabplan on tabrec.k02_codigo = tabplan.k02_codigo and tabplan.k02_anousu = ".db_getsession("DB_anousu");
     $sql .= "             inner join conplanoreduz on   conplanoreduz.c61_anousu=tabplan.k02_anousu and conplanoreduz.c61_reduz = tabplan.k02_reduz and conplanoreduz.c61_instit = ".db_getsession("DB_instit");
 		 $sql .= "             left join tabdescarretipo on k78_tabdesc  = tabdesc.codsubrec  ";
     $sql .= "             left join tabrecarretipo  on k79_receit   = tabrec.k02_codigo       ";
		 $sql .= "        where k02_anousu = ".db_getsession("DB_anousu");
     $sql .= "      ) as tabrec";
		 $sql .= " left join arretipo on tabrec.arretipo = arretipo.k00_tipo ";
     $sql2 = "";
     if($dbwhere==""){
      if($k02_codigo!=null ){
         $sql2 .= " where k07_codigo = $k07_codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql2 .= ($sql2!=""?" and ":" where ") . " (k02_limite is null or k02_limite >= '" . date('Y-m-d',db_getsession("DB_datausu")) . "')";
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
   function sql_query_semorcplan ( $k02_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from (";
  	 $sql .= " select k02_estorc,tabrec.* ";
  	 $sql .= " from tabrec ";
     $sql .= " left join taborc      on tabrec.k02_codigo     = taborc.k02_codigo
                               and taborc.k02_anousu     =  ".db_getsession("DB_anousu");
		 $sql .= "  where k02_estorc is null and tabrec.k02_tipo = 'O'  ";
  	 $sql .= " union ";
   	 $sql .= " select k02_estpla,tabrec.* ";
  	 $sql .= " from tabrec ";
     $sql .= " left join tabplan        on tabrec.k02_codigo = tabplan.k02_codigo
                               and tabplan.k02_anousu = ".db_getsession("DB_anousu");
  	 $sql .=  " where k02_estpla is null and tabrec.k02_tipo = 'E' ) as tabrec ";
     $sql2 = "";
     if($dbwhere==""){
      if($k02_codigo!=null ){
         $sql2 .= " where k02_codigo = $k02_codigo ";
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
     }else{
     	$sql .= " order by k02_codigo ";
   }

     return $sql;
  }

  function sql_query_recjur_recmul( $k02_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from tabrec ";
     $sql .= "      left join tabrec juros    on juros.k02_codigo    = tabrec.k02_recjur";
     $sql .= "      left join tabrec multa    on multa.k02_codigo    = tabrec.k02_recmul";

     $sql2 = "";
     if($dbwhere==""){
       if($k02_codigo!=null ){
         $sql2 .= " where tabrec.k02_codigo = $k02_codigo ";
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

  function sql_query_concarpeculiar($k02_codigo=null,$campos="*",$ordem=null,$dbwhere="") {
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
     $sql .= " from tabrec ";
     $sql .= "      inner join taborc     on taborc.k02_codigo     = tabrec.k02_codigo ";
     $sql .= "                           and taborc.k02_anousu     = ".db_getsession("DB_anousu");
     $sql .= "      inner join orcreceita on orcreceita.o70_codrec = taborc.k02_codrec ";
     $sql .= "                           and orcreceita.o70_anousu = ".db_getsession("DB_anousu");
     $sql2 = "";
     if($dbwhere==""){
       if($k02_codigo!=null ){
         $sql2 .= " where tabrec.k02_codigo = {$k02_codigo}";
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

  function sql_query_receita_extra_orcamentaria($k02_codigo=null,$campos="*",$ordem=null,$dbwhere="") {
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
    $sql .= " from tabrec ";
    $sql .= "      inner join tabplan        on tabplan.k02_codigo       = tabrec.k02_codigo ";
    $sql .= "      inner join conplanoreduz  on conplanoreduz.c61_reduz  = tabplan.k02_reduz ";
    $sql .= "                               and conplanoreduz.c61_anousu = tabplan.k02_anousu";

    $sql2 = "";
    if($dbwhere==""){
      if($k02_codigo!=null ){
        $sql2 .= " where tabrec.k02_codigo = {$k02_codigo}";
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
  
  function sql_query_verificaGrupoReceita($k02_codigo=null,$campos="*",$ordem=null,$dbwhere="") {
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
  	/*
  	 * 	tabrec 
				taborc  
				orcreceita
  	 */
  	 
  	$sql .= " from tabrec ";
  	$sql .= "      inner join taborc                 on tabrec.k02_codigo                   = taborc.k02_codigo                 ";
  	$sql .= "      inner join orcreceita             on taborc.k02_codrec                   = orcreceita.o70_codrec             ";
  	$sql .= "                                       and taborc.k02_anousu                   = orcreceita.o70_anousu             ";
  	$sql .= "      inner join conplanoorcamento      on orcreceita.o70_codfon               = conplanoorcamento.c60_codcon      ";
  	$sql .= "                                       and orcreceita.o70_anousu               = conplanoorcamento.c60_anousu      ";
  	$sql .= "      inner join conplanoorcamentogrupo on conplanoorcamento.c60_codcon        = conplanoorcamentogrupo.c21_codcon ";
  	$sql .= "                                       and conplanoorcamento.c60_anousu        = conplanoorcamentogrupo.c21_anousu ";
  	$sql .= "      inner join congrupo               on conplanoorcamentogrupo.c21_congrupo = congrupo.c20_sequencial           ";
  
  	$sql2 = "";
  	if($dbwhere==""){
  		if($k02_codigo!=null ){
  			$sql2 .= " where tabrec.k02_codigo = {$k02_codigo}";
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
  
  function sql_query_devolucaoAdiantamento ( $k02_codigo=null,$campos="*",$ordem=null,$dbwhere=""){

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
  	//$sql .= " from ( select k02_estorc,tabrec.k02_codigo, tabrec.k02_tipo, tabrec.k02_descr, tabrec.k02_drecei,  tabrec.k02_codjm, tabrec.k02_recjur, tabrec.k02_recmul, tabrec.k02_limite, o70_codrec,0 as c61_reduz,o70_codigo as recurso ";
  	$sql .= "        from tabrec ";
  	$sql .= "             inner join taborc on tabrec.k02_codigo = taborc.k02_codigo and taborc.k02_anousu = ".db_getsession("DB_anousu");
  	$sql .= "             inner join orcreceita on taborc.k02_codrec = orcreceita.o70_codrec and orcreceita.o70_anousu = ".db_getsession("DB_anousu")." and orcreceita.o70_instit = ".db_getsession("DB_instit");
  	if (USE_PCASP) {
  
  		$sql .= "  inner join conplanoorcamento on orcreceita.o70_anousu = conplanoorcamento.c60_anousu ";
  		$sql .= "                              and orcreceita.o70_codfon = conplanoorcamento.c60_codcon ";
  		$sql .= "      inner join conplanoorcamentogrupo on conplanoorcamento.c60_codcon        = conplanoorcamentogrupo.c21_codcon ";
  		$sql .= "                                       and conplanoorcamento.c60_anousu        = conplanoorcamentogrupo.c21_anousu ";
      $sql .= "                                       and conplanoorcamentogrupo.c21_instit   = " . db_getsession("DB_instit");
  		$sql .= "      inner join congrupo               on conplanoorcamentogrupo.c21_congrupo = congrupo.c20_sequencial           ";
  		
  	} else {
  		$sql .= "  inner join conplano on orcreceita.o70_anousu=conplano.c60_anousu and orcreceita.o70_codfon = conplano.c60_codcon ";
  	}
  	
  	$sql2 = "";
  	if($dbwhere==""){
  		if($k02_codigo!=null ){
  			$sql2 .= " where k02_codigo = $k02_codigo ";
  		}
  	}else if($dbwhere != ""){
  		$sql2 = " where $dbwhere";
  	}
  	$sql2 .= ($sql2!=""?" and ":" where ") . " (k02_limite is null or k02_limite >= '" . date('Y-m-d',db_getsession("DB_datausu")) . "')";
    $sql2 .= " and c20_sequencial = 11 and k02_anousu = ".db_getsession("DB_anousu");

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