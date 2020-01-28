<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE diariofinal
class cl_diariofinal {
   // cria variaveis de erro
   var $rotulo          = null;
   var $query_sql       = null;
   var $numrows         = 0;
   var $numrows_incluir = 0;
   var $numrows_alterar = 0;
   var $numrows_excluir = 0;
   var $erro_status     = null;
   var $erro_sql        = null;
   var $erro_banco      = null;
   var $erro_msg        = null;
   var $erro_campo      = null;
   var $pagina_retorno  = null;
   // cria variaveis do arquivo
   var $ed74_i_codigo        = 0;
   var $ed74_i_diario        = 0;
   var $ed74_i_procresultadoaprov        = 0;
   var $ed74_c_valoraprov        = null;
   var $ed74_c_resultadoaprov        = null;
   var $ed74_i_procresultadofreq        = 0;
   var $ed74_i_percfreq        = 0;
   var $ed74_c_resultadofreq        = null;
   var $ed74_c_resultadofinal        = null;
   var $ed74_i_calcfreq        = 0;
   var $ed74_t_obs        = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed74_i_codigo = int8 = Código
                 ed74_i_diario = int8 = Diário de Classe
                 ed74_i_procresultadoaprov = int8 = Resultado
                 ed74_c_valoraprov = char(10) = Valor do Aproveitamento
                 ed74_c_resultadoaprov = char(1) = Resultado Final do Aproveitamento
                 ed74_i_procresultadofreq = int8 = Resultado Frequência
                 ed74_i_percfreq = float4 = Percentual de Frequência
                 ed74_c_resultadofreq = char(1) = Resultado Final da Frequência
                 ed74_c_resultadofinal = char(1) = Resultado Final
                 ed74_i_calcfreq = int4 = Cálculo da Frequência
                 ed74_t_obs = text = Observações
                 ";
   //funcao construtor da classe
   function cl_diariofinal() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("diariofinal");
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
       $this->ed74_i_codigo = ($this->ed74_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed74_i_codigo"]:$this->ed74_i_codigo);
       $this->ed74_i_diario = ($this->ed74_i_diario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed74_i_diario"]:$this->ed74_i_diario);
       $this->ed74_i_procresultadoaprov = ($this->ed74_i_procresultadoaprov == ""?@$GLOBALS["HTTP_POST_VARS"]["ed74_i_procresultadoaprov"]:$this->ed74_i_procresultadoaprov);
       $this->ed74_c_valoraprov = ($this->ed74_c_valoraprov == ""?@$GLOBALS["HTTP_POST_VARS"]["ed74_c_valoraprov"]:$this->ed74_c_valoraprov);
       $this->ed74_c_resultadoaprov = ($this->ed74_c_resultadoaprov == ""?@$GLOBALS["HTTP_POST_VARS"]["ed74_c_resultadoaprov"]:$this->ed74_c_resultadoaprov);
       $this->ed74_i_procresultadofreq = ($this->ed74_i_procresultadofreq == ""?@$GLOBALS["HTTP_POST_VARS"]["ed74_i_procresultadofreq"]:$this->ed74_i_procresultadofreq);
       $this->ed74_i_percfreq = ($this->ed74_i_percfreq == ""?@$GLOBALS["HTTP_POST_VARS"]["ed74_i_percfreq"]:$this->ed74_i_percfreq);
       $this->ed74_c_resultadofreq = ($this->ed74_c_resultadofreq == ""?@$GLOBALS["HTTP_POST_VARS"]["ed74_c_resultadofreq"]:$this->ed74_c_resultadofreq);
       $this->ed74_c_resultadofinal = ($this->ed74_c_resultadofinal == ""?@$GLOBALS["HTTP_POST_VARS"]["ed74_c_resultadofinal"]:$this->ed74_c_resultadofinal);
       $this->ed74_i_calcfreq = ($this->ed74_i_calcfreq == ""?@$GLOBALS["HTTP_POST_VARS"]["ed74_i_calcfreq"]:$this->ed74_i_calcfreq);
       $this->ed74_t_obs = ($this->ed74_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ed74_t_obs"]:$this->ed74_t_obs);
     }else{
       $this->ed74_i_codigo = ($this->ed74_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed74_i_codigo"]:$this->ed74_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed74_i_codigo){
      $this->atualizacampos();
     if($this->ed74_i_diario == null ){
       $this->erro_sql = " Campo Diário de Classe nao Informado.";
       $this->erro_campo = "ed74_i_diario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed74_i_procresultadoaprov == null ){
       $this->ed74_i_procresultadoaprov = "null";
     }
     if($this->ed74_i_procresultadofreq == null ){
       $this->ed74_i_procresultadofreq = "null";
     }
     if($this->ed74_i_percfreq == null ){
       $this->ed74_i_percfreq = "null";
     }
     if($this->ed74_i_calcfreq == null ){
       $this->ed74_i_calcfreq = "null";
     }
     if($ed74_i_codigo == "" || $ed74_i_codigo == null ){
       $result = db_query("select nextval('diariofinal_ed74_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: diariofinal_ed74_i_codigo_seq do campo: ed74_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed74_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from diariofinal_ed74_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed74_i_codigo)){
         $this->erro_sql = " Campo ed74_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed74_i_codigo = $ed74_i_codigo;
       }
     }
     if(($this->ed74_i_codigo == null) || ($this->ed74_i_codigo == "") ){
       $this->erro_sql = " Campo ed74_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into diariofinal(
                                       ed74_i_codigo
                                      ,ed74_i_diario
                                      ,ed74_i_procresultadoaprov
                                      ,ed74_c_valoraprov
                                      ,ed74_c_resultadoaprov
                                      ,ed74_i_procresultadofreq
                                      ,ed74_i_percfreq
                                      ,ed74_c_resultadofreq
                                      ,ed74_c_resultadofinal
                                      ,ed74_i_calcfreq
                                      ,ed74_t_obs
                       )
                values (
                                $this->ed74_i_codigo
                               ,$this->ed74_i_diario
                               ,$this->ed74_i_procresultadoaprov
                               ,'$this->ed74_c_valoraprov'
                               ,'$this->ed74_c_resultadoaprov'
                               ,$this->ed74_i_procresultadofreq
                               ,$this->ed74_i_percfreq
                               ,'$this->ed74_c_resultadofreq'
                               ,'$this->ed74_c_resultadofinal'
                               ,$this->ed74_i_calcfreq
                               ,'$this->ed74_t_obs'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Resultado Final da disciplina ($this->ed74_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Resultado Final da disciplina já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Resultado Final da disciplina ($this->ed74_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed74_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {
       $resaco = $this->sql_record($this->sql_query_file($this->ed74_i_codigo));
       if(($resaco!=false)||($this->numrows!=0)){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008679,'$this->ed74_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010121,1008679,'','".AddSlashes(pg_result($resaco,0,'ed74_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010121,1008680,'','".AddSlashes(pg_result($resaco,0,'ed74_i_diario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010121,1008681,'','".AddSlashes(pg_result($resaco,0,'ed74_i_procresultadoaprov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010121,1008682,'','".AddSlashes(pg_result($resaco,0,'ed74_c_valoraprov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010121,1008683,'','".AddSlashes(pg_result($resaco,0,'ed74_c_resultadoaprov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010121,1008732,'','".AddSlashes(pg_result($resaco,0,'ed74_i_procresultadofreq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010121,1008684,'','".AddSlashes(pg_result($resaco,0,'ed74_i_percfreq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010121,1008685,'','".AddSlashes(pg_result($resaco,0,'ed74_c_resultadofreq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010121,1008686,'','".AddSlashes(pg_result($resaco,0,'ed74_c_resultadofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010121,14670,'','".AddSlashes(pg_result($resaco,0,'ed74_i_calcfreq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010121,17478,'','".AddSlashes(pg_result($resaco,0,'ed74_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ed74_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update diariofinal set ";
     $virgula = "";
     if(trim($this->ed74_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed74_i_codigo"])){
       $sql  .= $virgula." ed74_i_codigo = $this->ed74_i_codigo ";
       $virgula = ",";
       if(trim($this->ed74_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed74_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed74_i_diario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed74_i_diario"])){
       $sql  .= $virgula." ed74_i_diario = $this->ed74_i_diario ";
       $virgula = ",";
       if(trim($this->ed74_i_diario) == null ){
         $this->erro_sql = " Campo Diário de Classe nao Informado.";
         $this->erro_campo = "ed74_i_diario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed74_i_procresultadoaprov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed74_i_procresultadoaprov"])){
        if(trim($this->ed74_i_procresultadoaprov)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed74_i_procresultadoaprov"])){
           $this->ed74_i_procresultadoaprov = "0" ;
        }
       $sql  .= $virgula." ed74_i_procresultadoaprov = $this->ed74_i_procresultadoaprov ";
       $virgula = ",";
     }
     if(trim($this->ed74_c_valoraprov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed74_c_valoraprov"])){
       $sql  .= $virgula." ed74_c_valoraprov = '$this->ed74_c_valoraprov' ";
       $virgula = ",";
     }
     if(trim($this->ed74_c_resultadoaprov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed74_c_resultadoaprov"])){
       $sql  .= $virgula." ed74_c_resultadoaprov = '$this->ed74_c_resultadoaprov' ";
       $virgula = ",";
     }
     if(trim($this->ed74_i_procresultadofreq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed74_i_procresultadofreq"])){
        if(trim($this->ed74_i_procresultadofreq)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed74_i_procresultadofreq"])){
           $this->ed74_i_procresultadofreq = "0" ;
        }
       $sql  .= $virgula." ed74_i_procresultadofreq = $this->ed74_i_procresultadofreq ";
       $virgula = ",";
     }
     if(trim($this->ed74_i_percfreq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed74_i_percfreq"])){
        if(trim($this->ed74_i_percfreq)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed74_i_percfreq"])){
           $this->ed74_i_percfreq = "0" ;
        }
       $sql  .= $virgula." ed74_i_percfreq = $this->ed74_i_percfreq ";
       $virgula = ",";
     }
     if(trim($this->ed74_c_resultadofreq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed74_c_resultadofreq"])){
       $sql  .= $virgula." ed74_c_resultadofreq = '$this->ed74_c_resultadofreq' ";
       $virgula = ",";
     }
     if(trim($this->ed74_c_resultadofinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed74_c_resultadofinal"])){
       $sql  .= $virgula." ed74_c_resultadofinal = '$this->ed74_c_resultadofinal' ";
       $virgula = ",";
     }
     if(trim($this->ed74_i_calcfreq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed74_i_calcfreq"])){
        if(trim($this->ed74_i_calcfreq)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed74_i_calcfreq"])){
           $this->ed74_i_calcfreq = "0" ;
        }
       $sql  .= $virgula." ed74_i_calcfreq = $this->ed74_i_calcfreq ";
       $virgula = ",";
     }
     if(trim($this->ed74_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed74_t_obs"])){
       $sql  .= $virgula." ed74_t_obs = '$this->ed74_t_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed74_i_codigo!=null){
       $sql .= " ed74_i_codigo = $this->ed74_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {
       $resaco = $this->sql_record($this->sql_query_file($this->ed74_i_codigo));
       if($this->numrows>0){
         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1008679,'$this->ed74_i_codigo','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed74_i_codigo"]) || $this->ed74_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010121,1008679,'".AddSlashes(pg_result($resaco,$conresaco,'ed74_i_codigo'))."','$this->ed74_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed74_i_diario"]) || $this->ed74_i_diario != "")
             $resac = db_query("insert into db_acount values($acount,1010121,1008680,'".AddSlashes(pg_result($resaco,$conresaco,'ed74_i_diario'))."','$this->ed74_i_diario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed74_i_procresultadoaprov"]) || $this->ed74_i_procresultadoaprov != "")
             $resac = db_query("insert into db_acount values($acount,1010121,1008681,'".AddSlashes(pg_result($resaco,$conresaco,'ed74_i_procresultadoaprov'))."','$this->ed74_i_procresultadoaprov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed74_c_valoraprov"]) || $this->ed74_c_valoraprov != "")
             $resac = db_query("insert into db_acount values($acount,1010121,1008682,'".AddSlashes(pg_result($resaco,$conresaco,'ed74_c_valoraprov'))."','$this->ed74_c_valoraprov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed74_c_resultadoaprov"]) || $this->ed74_c_resultadoaprov != "")
             $resac = db_query("insert into db_acount values($acount,1010121,1008683,'".AddSlashes(pg_result($resaco,$conresaco,'ed74_c_resultadoaprov'))."','$this->ed74_c_resultadoaprov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed74_i_procresultadofreq"]) || $this->ed74_i_procresultadofreq != "")
             $resac = db_query("insert into db_acount values($acount,1010121,1008732,'".AddSlashes(pg_result($resaco,$conresaco,'ed74_i_procresultadofreq'))."','$this->ed74_i_procresultadofreq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed74_i_percfreq"]) || $this->ed74_i_percfreq != "")
             $resac = db_query("insert into db_acount values($acount,1010121,1008684,'".AddSlashes(pg_result($resaco,$conresaco,'ed74_i_percfreq'))."','$this->ed74_i_percfreq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed74_c_resultadofreq"]) || $this->ed74_c_resultadofreq != "")
             $resac = db_query("insert into db_acount values($acount,1010121,1008685,'".AddSlashes(pg_result($resaco,$conresaco,'ed74_c_resultadofreq'))."','$this->ed74_c_resultadofreq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed74_c_resultadofinal"]) || $this->ed74_c_resultadofinal != "")
             $resac = db_query("insert into db_acount values($acount,1010121,1008686,'".AddSlashes(pg_result($resaco,$conresaco,'ed74_c_resultadofinal'))."','$this->ed74_c_resultadofinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed74_i_calcfreq"]) || $this->ed74_i_calcfreq != "")
             $resac = db_query("insert into db_acount values($acount,1010121,14670,'".AddSlashes(pg_result($resaco,$conresaco,'ed74_i_calcfreq'))."','$this->ed74_i_calcfreq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed74_t_obs"]) || $this->ed74_t_obs != "")
             $resac = db_query("insert into db_acount values($acount,1010121,17478,'".AddSlashes(pg_result($resaco,$conresaco,'ed74_t_obs'))."','$this->ed74_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
     }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Resultado Final da disciplina nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed74_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Resultado Final da disciplina nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed74_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed74_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ed74_i_codigo=null,$dbwhere=null) {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {
       if($dbwhere==null || $dbwhere==""){
         $resaco = $this->sql_record($this->sql_query_file($ed74_i_codigo));
       }else{
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if(($resaco!=false)||($this->numrows!=0)){
         for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1008679,'$ed74_i_codigo','E')");
           $resac = db_query("insert into db_acount values($acount,1010121,1008679,'','".AddSlashes(pg_result($resaco,$iresaco,'ed74_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,1010121,1008680,'','".AddSlashes(pg_result($resaco,$iresaco,'ed74_i_diario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,1010121,1008681,'','".AddSlashes(pg_result($resaco,$iresaco,'ed74_i_procresultadoaprov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,1010121,1008682,'','".AddSlashes(pg_result($resaco,$iresaco,'ed74_c_valoraprov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,1010121,1008683,'','".AddSlashes(pg_result($resaco,$iresaco,'ed74_c_resultadoaprov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,1010121,1008732,'','".AddSlashes(pg_result($resaco,$iresaco,'ed74_i_procresultadofreq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,1010121,1008684,'','".AddSlashes(pg_result($resaco,$iresaco,'ed74_i_percfreq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,1010121,1008685,'','".AddSlashes(pg_result($resaco,$iresaco,'ed74_c_resultadofreq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,1010121,1008686,'','".AddSlashes(pg_result($resaco,$iresaco,'ed74_c_resultadofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,1010121,14670,'','".AddSlashes(pg_result($resaco,$iresaco,'ed74_i_calcfreq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,1010121,17478,'','".AddSlashes(pg_result($resaco,$iresaco,'ed74_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from diariofinal
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed74_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed74_i_codigo = $ed74_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Resultado Final da disciplina nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed74_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Resultado Final da disciplina nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed74_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed74_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:diariofinal";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $ed74_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from diariofinal ";
     $sql .= "      inner join diario  on  diario.ed95_i_codigo = diariofinal.ed74_i_diario";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = diario.ed95_i_escola";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = diario.ed95_i_serie";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = diario.ed95_i_aluno";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = diario.ed95_i_calendario";
     $sql .= "      inner join regencia  on  regencia.ed59_i_codigo = diario.ed95_i_regencia";
     $sql .= "      left join procresultado as procaprov on  procaprov.ed43_i_codigo = diariofinal.ed74_i_procresultadoaprov";
     $sql .= "      left join procresultado as procfreq on  procfreq.ed43_i_codigo = diariofinal.ed74_i_procresultadofreq";
     $sql .= "      left join formaavaliacao  on  formaavaliacao.ed37_i_codigo = procaprov.ed43_i_formaavaliacao";
     $sql .= "      left join procedimento  on  procedimento.ed40_i_codigo = procaprov.ed43_i_procedimento";
     $sql .= "      left join resultado  on  resultado.ed42_i_codigo = procaprov.ed43_i_resultado";
     $sql .= "      left join formaavaliacao  as a on   a.ed37_i_codigo = procfreq.ed43_i_formaavaliacao";
     $sql .= "      left join procedimento  as b on   b.ed40_i_codigo = procfreq.ed43_i_procedimento";
     $sql .= "      left join resultado  as c on   c.ed42_i_codigo = procfreq.ed43_i_resultado";
     $sql2 = "";
     if($dbwhere==""){
       if($ed74_i_codigo!=null ){
         $sql2 .= " where diariofinal.ed74_i_codigo = $ed74_i_codigo ";
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
   function sql_query_file ( $ed74_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from diariofinal ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed74_i_codigo!=null ){
         $sql2 .= " where diariofinal.ed74_i_codigo = $ed74_i_codigo ";
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

  function sql_query_relqdfinal($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';

      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }

    $sSql .= " FROM diariofinal ";
    $sSql .= "      inner join diario on ed95_i_codigo = ed74_i_diario ";
    $sSql .= "      inner join regencia on ed59_i_codigo = ed95_i_regencia ";
    $sSql .= "      inner join disciplina on ed12_i_codigo = ed59_i_disciplina ";
    $ssql .= "      inner join caddisciplina on ed232_i_codigo = ed12_i_caddisciplina ";
    $sSql .= "      left join amparo on ed81_i_diario = ed95_i_codigo ";
    $ssql .= "      left join convencaoamp on ed250_i_codigo = ed81_i_convencaoamp ";
    $sSql .= "      left join procresultado on ed43_i_codigo = ed74_i_procresultadoaprov ";
    $sSql .= "      left join formaavaliacao on ed37_i_codigo = ed43_i_formaavaliacao ";

    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where matricula.ed60_i_codigo = $iCodigo ";
      }

    } else if ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }

    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';

      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }

  function sql_query_relquadfinal($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';

      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }

    $sSql .= " FROM diariofinal ";
    $sSql .= "      inner join diario on ed95_i_codigo = ed74_i_diario ";
    $sSql .= "      inner join regencia on ed59_i_codigo = ed95_i_regencia ";
    $sSql .= "      inner join turma on ed57_i_codigo = ed59_i_turma ";

    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where matricula.ed60_i_codigo = $iCodigo ";
      }

    } else if ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }

    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';

      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;
  }
}
?>