<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: escola
//CLASSE DA ENTIDADE conceito
class cl_conceito {
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
   var $ed39_i_codigo = 0;
   var $ed39_i_formaavaliacao = 0;
   var $ed39_c_conceito = null;
   var $ed39_c_conceitodescr = null;
   var $ed39_i_sequencia = 0;
   var $ed39_c_nome = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed39_i_codigo = int8 = C�digo
                 ed39_i_formaavaliacao = int8 = Forma de Avalia��o
                 ed39_c_conceito = char(3) = Sigla
                 ed39_c_conceitodescr = char(100) = Descri��o do N�vel
                 ed39_i_sequencia = int4 = Ordena��o
                 ed39_c_nome = char(30) = Nome
                 ";
   //funcao construtor da classe
   function cl_conceito() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conceito");
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
       $this->ed39_i_codigo = ($this->ed39_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed39_i_codigo"]:$this->ed39_i_codigo);
       $this->ed39_i_formaavaliacao = ($this->ed39_i_formaavaliacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed39_i_formaavaliacao"]:$this->ed39_i_formaavaliacao);
       $this->ed39_c_conceito = ($this->ed39_c_conceito == ""?@$GLOBALS["HTTP_POST_VARS"]["ed39_c_conceito"]:$this->ed39_c_conceito);
       $this->ed39_c_conceitodescr = ($this->ed39_c_conceitodescr == ""?@$GLOBALS["HTTP_POST_VARS"]["ed39_c_conceitodescr"]:$this->ed39_c_conceitodescr);
       $this->ed39_i_sequencia = ($this->ed39_i_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed39_i_sequencia"]:$this->ed39_i_sequencia);
       $this->ed39_c_nome = ($this->ed39_c_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["ed39_c_nome"]:$this->ed39_c_nome);
     }else{
       $this->ed39_i_codigo = ($this->ed39_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed39_i_codigo"]:$this->ed39_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed39_i_codigo){
      $this->atualizacampos();
     if($this->ed39_i_formaavaliacao == null ){
       $this->erro_sql = " Campo Forma de Avalia��o nao Informado.";
       $this->erro_campo = "ed39_i_formaavaliacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed39_c_conceito == null ){
       $this->erro_sql = " Campo Sigla nao Informado.";
       $this->erro_campo = "ed39_c_conceito";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed39_c_conceitodescr == null ){
       $this->erro_sql = " Campo Descri��o do N�vel nao Informado.";
       $this->erro_campo = "ed39_c_conceitodescr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed39_i_sequencia == null ){
       $this->erro_sql = " Campo Ordena��o nao Informado.";
       $this->erro_campo = "ed39_i_sequencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed39_c_nome == null ){
       $this->erro_sql = " Campo Nome nao Informado.";
       $this->erro_campo = "ed39_c_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed39_i_codigo == "" || $ed39_i_codigo == null ){
       $result = db_query("select nextval('conceito_ed39_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: conceito_ed39_i_codigo_seq do campo: ed39_i_codigo";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed39_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from conceito_ed39_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed39_i_codigo)){
         $this->erro_sql = " Campo ed39_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed39_i_codigo = $ed39_i_codigo;
       }
     }
     if(($this->ed39_i_codigo == null) || ($this->ed39_i_codigo == "") ){
       $this->erro_sql = " Campo ed39_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conceito(
                                       ed39_i_codigo
                                      ,ed39_i_formaavaliacao
                                      ,ed39_c_conceito
                                      ,ed39_c_conceitodescr
                                      ,ed39_i_sequencia
                                      ,ed39_c_nome
                       )
                values (
                                $this->ed39_i_codigo
                               ,$this->ed39_i_formaavaliacao
                               ,'$this->ed39_c_conceito'
                               ,'$this->ed39_c_conceitodescr'
                               ,$this->ed39_i_sequencia
                               ,'$this->ed39_c_nome'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "N�veis/Conceitos da Forma de Avalia��o ($this->ed39_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "N�veis/Conceitos da Forma de Avalia��o j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "N�veis/Conceitos da Forma de Avalia��o ($this->ed39_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed39_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed39_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008428,'$this->ed39_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010072,1008428,'','".AddSlashes(pg_result($resaco,0,'ed39_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010072,1008429,'','".AddSlashes(pg_result($resaco,0,'ed39_i_formaavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010072,1008430,'','".AddSlashes(pg_result($resaco,0,'ed39_c_conceito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010072,1008431,'','".AddSlashes(pg_result($resaco,0,'ed39_c_conceitodescr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010072,1008432,'','".AddSlashes(pg_result($resaco,0,'ed39_i_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010072,14176,'','".AddSlashes(pg_result($resaco,0,'ed39_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ed39_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update conceito set ";
     $virgula = "";
     if(trim($this->ed39_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed39_i_codigo"])){
       $sql  .= $virgula." ed39_i_codigo = $this->ed39_i_codigo ";
       $virgula = ",";
       if(trim($this->ed39_i_codigo) == null ){
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "ed39_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed39_i_formaavaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed39_i_formaavaliacao"])){
       $sql  .= $virgula." ed39_i_formaavaliacao = $this->ed39_i_formaavaliacao ";
       $virgula = ",";
       if(trim($this->ed39_i_formaavaliacao) == null ){
         $this->erro_sql = " Campo Forma de Avalia��o nao Informado.";
         $this->erro_campo = "ed39_i_formaavaliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed39_c_conceito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed39_c_conceito"])){
       $sql  .= $virgula." ed39_c_conceito = '$this->ed39_c_conceito' ";
       $virgula = ",";
       if(trim($this->ed39_c_conceito) == null ){
         $this->erro_sql = " Campo Sigla nao Informado.";
         $this->erro_campo = "ed39_c_conceito";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed39_c_conceitodescr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed39_c_conceitodescr"])){
       $sql  .= $virgula." ed39_c_conceitodescr = '$this->ed39_c_conceitodescr' ";
       $virgula = ",";
       if(trim($this->ed39_c_conceitodescr) == null ){
         $this->erro_sql = " Campo Descri��o do N�vel nao Informado.";
         $this->erro_campo = "ed39_c_conceitodescr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed39_i_sequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed39_i_sequencia"])){
       $sql  .= $virgula." ed39_i_sequencia = $this->ed39_i_sequencia ";
       $virgula = ",";
       if(trim($this->ed39_i_sequencia) == null ){
         $this->erro_sql = " Campo Ordena��o nao Informado.";
         $this->erro_campo = "ed39_i_sequencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed39_c_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed39_c_nome"])){
       $sql  .= $virgula." ed39_c_nome = '$this->ed39_c_nome' ";
       $virgula = ",";
       if(trim($this->ed39_c_nome) == null ){
         $this->erro_sql = " Campo Nome nao Informado.";
         $this->erro_campo = "ed39_c_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed39_i_codigo!=null){
       $sql .= " ed39_i_codigo = $this->ed39_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed39_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008428,'$this->ed39_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed39_i_codigo"]) || $this->ed39_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,1010072,1008428,'".AddSlashes(pg_result($resaco,$conresaco,'ed39_i_codigo'))."','$this->ed39_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed39_i_formaavaliacao"]) || $this->ed39_i_formaavaliacao != "")
           $resac = db_query("insert into db_acount values($acount,1010072,1008429,'".AddSlashes(pg_result($resaco,$conresaco,'ed39_i_formaavaliacao'))."','$this->ed39_i_formaavaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed39_c_conceito"]) || $this->ed39_c_conceito != "")
           $resac = db_query("insert into db_acount values($acount,1010072,1008430,'".AddSlashes(pg_result($resaco,$conresaco,'ed39_c_conceito'))."','$this->ed39_c_conceito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed39_c_conceitodescr"]) || $this->ed39_c_conceitodescr != "")
           $resac = db_query("insert into db_acount values($acount,1010072,1008431,'".AddSlashes(pg_result($resaco,$conresaco,'ed39_c_conceitodescr'))."','$this->ed39_c_conceitodescr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed39_i_sequencia"]) || $this->ed39_i_sequencia != "")
           $resac = db_query("insert into db_acount values($acount,1010072,1008432,'".AddSlashes(pg_result($resaco,$conresaco,'ed39_i_sequencia'))."','$this->ed39_i_sequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed39_c_nome"]) || $this->ed39_c_nome != "")
           $resac = db_query("insert into db_acount values($acount,1010072,14176,'".AddSlashes(pg_result($resaco,$conresaco,'ed39_c_nome'))."','$this->ed39_c_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "N�veis/Conceitos da Forma de Avalia��o nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed39_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "N�veis/Conceitos da Forma de Avalia��o nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed39_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed39_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ed39_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed39_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008428,'$ed39_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010072,1008428,'','".AddSlashes(pg_result($resaco,$iresaco,'ed39_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010072,1008429,'','".AddSlashes(pg_result($resaco,$iresaco,'ed39_i_formaavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010072,1008430,'','".AddSlashes(pg_result($resaco,$iresaco,'ed39_c_conceito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010072,1008431,'','".AddSlashes(pg_result($resaco,$iresaco,'ed39_c_conceitodescr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010072,1008432,'','".AddSlashes(pg_result($resaco,$iresaco,'ed39_i_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010072,14176,'','".AddSlashes(pg_result($resaco,$iresaco,'ed39_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from conceito
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed39_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed39_i_codigo = $ed39_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "N�veis/Conceitos da Forma de Avalia��o nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed39_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "N�veis/Conceitos da Forma de Avalia��o nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed39_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed39_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:conceito";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $ed39_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conceito ";
     $sql .= "      inner join formaavaliacao  on  formaavaliacao.ed37_i_codigo = conceito.ed39_i_formaavaliacao";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = formaavaliacao.ed37_i_escola";
     $sql2 = "";
     if($dbwhere==""){
       if($ed39_i_codigo!=null ){
         $sql2 .= " where conceito.ed39_i_codigo = $ed39_i_codigo ";
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
   function sql_query_file ( $ed39_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conceito ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed39_i_codigo!=null ){
         $sql2 .= " where conceito.ed39_i_codigo = $ed39_i_codigo ";
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