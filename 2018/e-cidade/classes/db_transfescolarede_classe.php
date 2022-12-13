<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: educação
//CLASSE DA ENTIDADE transfescolarede
class cl_transfescolarede {
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
   var $ed103_i_codigo = 0;
   var $ed103_i_matricula = 0;
   var $ed103_i_escolaorigem = 0;
   var $ed103_i_atestvaga = 0;
   var $ed103_i_usuario = 0;
   var $ed103_d_data_dia = null;
   var $ed103_d_data_mes = null;
   var $ed103_d_data_ano = null;
   var $ed103_d_data = null;
   var $ed103_t_obs = null;
   var $ed103_c_situacao = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed103_i_codigo = int8 = Código
                 ed103_i_matricula = int8 = Matrícula
                 ed103_i_escolaorigem = int8 = Escola Origem
                 ed103_i_atestvaga = int8 = Código Atestado
                 ed103_i_usuario = int8 = Usuário
                 ed103_d_data = date = Data
                 ed103_t_obs = text = Observações
                 ed103_c_situacao = char(1) = Situação
                 ";
   //funcao construtor da classe
   function cl_transfescolarede() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("transfescolarede");
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
       $this->ed103_i_codigo = ($this->ed103_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed103_i_codigo"]:$this->ed103_i_codigo);
       $this->ed103_i_matricula = ($this->ed103_i_matricula == ""?@$GLOBALS["HTTP_POST_VARS"]["ed103_i_matricula"]:$this->ed103_i_matricula);
       $this->ed103_i_escolaorigem = ($this->ed103_i_escolaorigem == ""?@$GLOBALS["HTTP_POST_VARS"]["ed103_i_escolaorigem"]:$this->ed103_i_escolaorigem);
       $this->ed103_i_atestvaga = ($this->ed103_i_atestvaga == ""?@$GLOBALS["HTTP_POST_VARS"]["ed103_i_atestvaga"]:$this->ed103_i_atestvaga);
       $this->ed103_i_usuario = ($this->ed103_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed103_i_usuario"]:$this->ed103_i_usuario);
       if($this->ed103_d_data == ""){
         $this->ed103_d_data_dia = ($this->ed103_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed103_d_data_dia"]:$this->ed103_d_data_dia);
         $this->ed103_d_data_mes = ($this->ed103_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed103_d_data_mes"]:$this->ed103_d_data_mes);
         $this->ed103_d_data_ano = ($this->ed103_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed103_d_data_ano"]:$this->ed103_d_data_ano);
         if($this->ed103_d_data_dia != ""){
            $this->ed103_d_data = $this->ed103_d_data_ano."-".$this->ed103_d_data_mes."-".$this->ed103_d_data_dia;
         }
       }
       $this->ed103_t_obs = ($this->ed103_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ed103_t_obs"]:$this->ed103_t_obs);
       $this->ed103_c_situacao = ($this->ed103_c_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed103_c_situacao"]:$this->ed103_c_situacao);
     }else{
       $this->ed103_i_codigo = ($this->ed103_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed103_i_codigo"]:$this->ed103_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed103_i_codigo){
      $this->atualizacampos();
     if($this->ed103_i_atestvaga == null ){
       $this->erro_sql = " Campo Código Atestado nao Informado.";
       $this->erro_campo = "ed103_i_atestvaga";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed103_i_matricula == null ){
       $this->erro_sql = " Campo Matrícula nao Informado.";
       $this->erro_campo = "ed103_i_matricula";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed103_i_escolaorigem == null ){
       $this->erro_sql = " Campo Escola Origem nao Informado.";
       $this->erro_campo = "ed103_i_escolaorigem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed103_i_usuario == null ){
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "ed103_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed103_d_data == null ){
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "ed103_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed103_c_situacao == null ){
       $this->erro_sql = " Campo Situação nao Informado.";
       $this->erro_campo = "ed103_c_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed103_i_codigo == "" || $ed103_i_codigo == null ){
       $result = db_query("select nextval('transfescolarede_ed103_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: transfescolarede_ed103_i_codigo_seq do campo: ed103_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed103_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from transfescolarede_ed103_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed103_i_codigo)){
         $this->erro_sql = " Campo ed103_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed103_i_codigo = $ed103_i_codigo;
       }
     }
     if(($this->ed103_i_codigo == null) || ($this->ed103_i_codigo == "") ){
       $this->erro_sql = " Campo ed103_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into transfescolarede(
                                       ed103_i_codigo
                                      ,ed103_i_matricula
                                      ,ed103_i_escolaorigem
                                      ,ed103_i_atestvaga
                                      ,ed103_i_usuario
                                      ,ed103_d_data
                                      ,ed103_t_obs
                                      ,ed103_c_situacao
                       )
                values (
                                $this->ed103_i_codigo
                               ,$this->ed103_i_matricula
                               ,$this->ed103_i_escolaorigem
                               ,$this->ed103_i_atestvaga
                               ,$this->ed103_i_usuario
                               ,".($this->ed103_d_data == "null" || $this->ed103_d_data == ""?"null":"'".$this->ed103_d_data."'")."
                               ,'$this->ed103_t_obs'
                               ,'$this->ed103_c_situacao'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Transferência de escolas da rede ($this->ed103_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Transferência de escolas da rede já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Transferência de escolas da rede ($this->ed103_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed103_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed103_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1009045,'$this->ed103_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010162,1009045,'','".AddSlashes(pg_result($resaco,0,'ed103_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010162,1009060,'','".AddSlashes(pg_result($resaco,0,'ed103_i_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010162,1009046,'','".AddSlashes(pg_result($resaco,0,'ed103_i_escolaorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010162,1009047,'','".AddSlashes(pg_result($resaco,0,'ed103_i_atestvaga'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010162,1009048,'','".AddSlashes(pg_result($resaco,0,'ed103_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010162,1009049,'','".AddSlashes(pg_result($resaco,0,'ed103_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010162,1009050,'','".AddSlashes(pg_result($resaco,0,'ed103_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010162,1009061,'','".AddSlashes(pg_result($resaco,0,'ed103_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ed103_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update transfescolarede set ";
     $virgula = "";
     if(trim($this->ed103_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed103_i_codigo"])){
       $sql  .= $virgula." ed103_i_codigo = $this->ed103_i_codigo ";
       $virgula = ",";
       if(trim($this->ed103_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed103_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed103_i_matricula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed103_i_matricula"])){
       $sql  .= $virgula." ed103_i_matricula = $this->ed103_i_matricula ";
       $virgula = ",";
       if(trim($this->ed103_i_matricula) == null ){
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "ed103_i_matricula";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed103_i_escolaorigem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed103_i_escolaorigem"])){
       $sql  .= $virgula." ed103_i_escolaorigem = $this->ed103_i_escolaorigem ";
       $virgula = ",";
       if(trim($this->ed103_i_escolaorigem) == null ){
         $this->erro_sql = " Campo Escola Origem nao Informado.";
         $this->erro_campo = "ed103_i_escolaorigem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed103_i_atestvaga)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed103_i_atestvaga"])){
       $sql  .= $virgula." ed103_i_atestvaga = $this->ed103_i_atestvaga ";
       $virgula = ",";
       if(trim($this->ed103_i_atestvaga) == null ){
         $this->erro_sql = " Campo Código Atestado nao Informado.";
         $this->erro_campo = "ed103_i_atestvaga";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed103_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed103_i_usuario"])){
       $sql  .= $virgula." ed103_i_usuario = $this->ed103_i_usuario ";
       $virgula = ",";
       if(trim($this->ed103_i_usuario) == null ){
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "ed103_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed103_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed103_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed103_d_data_dia"] !="") ){
       $sql  .= $virgula." ed103_d_data = '$this->ed103_d_data' ";
       $virgula = ",";
       if(trim($this->ed103_d_data) == null ){
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "ed103_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed103_d_data_dia"])){
         $sql  .= $virgula." ed103_d_data = null ";
         $virgula = ",";
         if(trim($this->ed103_d_data) == null ){
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "ed103_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed103_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed103_t_obs"])){
       $sql  .= $virgula." ed103_t_obs = '$this->ed103_t_obs' ";
       $virgula = ",";
     }
     if(trim($this->ed103_c_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed103_c_situacao"])){
       $sql  .= $virgula." ed103_c_situacao = '$this->ed103_c_situacao' ";
       $virgula = ",";
       if(trim($this->ed103_c_situacao) == null ){
         $this->erro_sql = " Campo Situação nao Informado.";
         $this->erro_campo = "ed103_c_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed103_i_codigo!=null){
       $sql .= " ed103_i_codigo = $this->ed103_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed103_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009045,'$this->ed103_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed103_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010162,1009045,'".AddSlashes(pg_result($resaco,$conresaco,'ed103_i_codigo'))."','$this->ed103_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed103_i_matricula"]))
           $resac = db_query("insert into db_acount values($acount,1010162,1009060,'".AddSlashes(pg_result($resaco,$conresaco,'ed103_i_matricula'))."','$this->ed103_i_matricula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed103_i_escolaorigem"]))
           $resac = db_query("insert into db_acount values($acount,1010162,1009046,'".AddSlashes(pg_result($resaco,$conresaco,'ed103_i_escolaorigem'))."','$this->ed103_i_escolaorigem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed103_i_atestvaga"]))
           $resac = db_query("insert into db_acount values($acount,1010162,1009047,'".AddSlashes(pg_result($resaco,$conresaco,'ed103_i_atestvaga'))."','$this->ed103_i_atestvaga',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed103_i_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1010162,1009048,'".AddSlashes(pg_result($resaco,$conresaco,'ed103_i_usuario'))."','$this->ed103_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed103_d_data"]))
           $resac = db_query("insert into db_acount values($acount,1010162,1009049,'".AddSlashes(pg_result($resaco,$conresaco,'ed103_d_data'))."','$this->ed103_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed103_t_obs"]))
           $resac = db_query("insert into db_acount values($acount,1010162,1009050,'".AddSlashes(pg_result($resaco,$conresaco,'ed103_t_obs'))."','$this->ed103_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed103_c_situacao"]))
           $resac = db_query("insert into db_acount values($acount,1010162,1009061,'".AddSlashes(pg_result($resaco,$conresaco,'ed103_c_situacao'))."','$this->ed103_c_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Transferência de escolas da rede nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed103_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Transferência de escolas da rede nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed103_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed103_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ed103_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed103_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009045,'$ed103_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010162,1009045,'','".AddSlashes(pg_result($resaco,$iresaco,'ed103_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010162,1009060,'','".AddSlashes(pg_result($resaco,$iresaco,'ed103_i_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010162,1009046,'','".AddSlashes(pg_result($resaco,$iresaco,'ed103_i_escolaorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010162,1009047,'','".AddSlashes(pg_result($resaco,$iresaco,'ed103_i_atestvaga'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010162,1009048,'','".AddSlashes(pg_result($resaco,$iresaco,'ed103_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010162,1009049,'','".AddSlashes(pg_result($resaco,$iresaco,'ed103_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010162,1009050,'','".AddSlashes(pg_result($resaco,$iresaco,'ed103_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010162,1009061,'','".AddSlashes(pg_result($resaco,$iresaco,'ed103_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from transfescolarede
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed103_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed103_i_codigo = $ed103_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);     
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Transferência de escolas da rede nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed103_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Transferência de escolas da rede nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed103_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed103_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:transfescolarede";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed103_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from transfescolarede ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = transfescolarede.ed103_i_usuario";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = transfescolarede.ed103_i_escolaorigem";
     $sql .= "      inner join censouf  on  censouf.ed260_i_codigo = escola.ed18_i_censouf";
     $sql .= "      inner join censomunic  on  censomunic.ed261_i_codigo = escola.ed18_i_censomunic";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql .= "      inner join matricula  on  matricula.ed60_i_codigo = transfescolarede.ed103_i_matricula";
     $sql .= "      inner join matriculaserie  on  matriculaserie.ed221_i_matricula = matricula.ed60_i_codigo";
     $sql .= "      inner join turma  on  turma.ed57_i_codigo = matricula.ed60_i_turma";
     $sql .= "      inner join atestvaga  on  atestvaga.ed102_i_codigo = transfescolarede.ed103_i_atestvaga";
     $sql .= "      inner join escola  as escoladestino on  escoladestino.ed18_i_codigo = atestvaga.ed102_i_escola";
     $sql .= "      inner join censouf as censoufdestino on  censoufdestino.ed260_i_codigo = escoladestino.ed18_i_censouf";
     $sql .= "      inner join censomunic as censomunicdestino  on  censomunicdestino.ed261_i_codigo = escoladestino.ed18_i_censomunic";
     $sql .= "      inner join turno  on  turno.ed15_i_codigo = atestvaga.ed102_i_turno";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = atestvaga.ed102_i_serie";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = serie.ed11_i_ensino";
     $sql .= "      inner join aluno  on   aluno.ed47_i_codigo = atestvaga.ed102_i_aluno";
     $sql .= "      left  join censouf as censoufident on  censoufident.ed260_i_codigo = aluno.ed47_i_censoufident";
     $sql .= "      left  join censouf as censoufnat on  censoufnat.ed260_i_codigo = aluno.ed47_i_censoufnat";
     $sql .= "      left  join censouf as censoufcert on  censoufcert.ed260_i_codigo = aluno.ed47_i_censoufcert";
     $sql .= "      left  join censouf as censoufend on  censoufend.ed260_i_codigo = aluno.ed47_i_censoufend";
     $sql .= "      left  join censomunic as censomunicnat on  censomunicnat.ed261_i_codigo = aluno.ed47_i_censomunicnat";
     $sql .= "      left  join censomunic as censomuniccert on  censomuniccert.ed261_i_codigo = aluno.ed47_i_censomuniccert";
     $sql .= "      left  join censomunic as censomunicend on  censomunicend.ed261_i_codigo = aluno.ed47_i_censomunicend";
     $sql .= "      left  join censoorgemissrg  on  censoorgemissrg.ed132_i_codigo = aluno.ed47_i_censoorgemissrg";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = atestvaga.ed102_i_calendario";
     $sql .= "      inner join base  on  base.ed31_i_codigo = atestvaga.ed102_i_base";
     $sql .= "      inner join cursoedu  on  cursoedu.ed29_i_codigo = base.ed31_i_curso";     
     $sql2 = "";
     if($dbwhere==""){
       if($ed103_i_codigo!=null ){
         $sql2 .= " where matriculaserie.ed221_c_origem = 'S' AND transfescolarede.ed103_i_codigo = $ed103_i_codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where matriculaserie.ed221_c_origem = 'S' AND $dbwhere";
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
  function sql_query_historico ( $ed103_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from transfescolarede ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = transfescolarede.ed103_i_usuario";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = transfescolarede.ed103_i_escolaorigem";
     $sql .= "      inner join censouf  on  censouf.ed260_i_codigo = escola.ed18_i_censouf";
     $sql .= "      inner join censomunic  on  censomunic.ed261_i_codigo = escola.ed18_i_censomunic";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql .= "      inner join matricula  on  matricula.ed60_i_codigo = transfescolarede.ed103_i_matricula";
     $sql .= "      inner join matriculaserie  on  matriculaserie.ed221_i_matricula = matricula.ed60_i_codigo";
     $sql .= "      inner join turma  on  turma.ed57_i_codigo = matricula.ed60_i_turma";
     $sql .= "      inner join atestvaga  on  atestvaga.ed102_i_codigo = transfescolarede.ed103_i_atestvaga";
     $sql .= "      inner join escola  as escoladestino on  escoladestino.ed18_i_codigo = atestvaga.ed102_i_escola";
     $sql .= "      inner join censouf as censoufdestino on  censoufdestino.ed260_i_codigo = escoladestino.ed18_i_censouf";
     $sql .= "      inner join censomunic as censomunicdestino  on  censomunicdestino.ed261_i_codigo = escoladestino.ed18_i_censomunic";
     $sql .= "      inner join turno  on  turno.ed15_i_codigo = atestvaga.ed102_i_turno";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = atestvaga.ed102_i_serie";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = serie.ed11_i_ensino";
     $sql .= "      inner join aluno  on   aluno.ed47_i_codigo = atestvaga.ed102_i_aluno";
     $sql .= "      inner join historico  on   historico.ed61_i_aluno = aluno.ed47_i_codigo";
     $sql .= "      left  join censouf as censoufident on  censoufident.ed260_i_codigo = aluno.ed47_i_censoufident";
     $sql .= "      left  join censouf as censoufnat on  censoufnat.ed260_i_codigo = aluno.ed47_i_censoufnat";
     $sql .= "      left  join censouf as censoufcert on  censoufcert.ed260_i_codigo = aluno.ed47_i_censoufcert";
     $sql .= "      left  join censouf as censoufend on  censoufend.ed260_i_codigo = aluno.ed47_i_censoufend";
     $sql .= "      left  join censomunic as censomunicnat on  censomunicnat.ed261_i_codigo = aluno.ed47_i_censomunicnat";
     $sql .= "      left  join censomunic as censomuniccert on  censomuniccert.ed261_i_codigo = aluno.ed47_i_censomuniccert";
     $sql .= "      left  join censomunic as censomunicend on  censomunicend.ed261_i_codigo = aluno.ed47_i_censomunicend";
     $sql .= "      left  join censoorgemissrg  on  censoorgemissrg.ed132_i_codigo = aluno.ed47_i_censoorgemissrg";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = atestvaga.ed102_i_calendario";
     $sql .= "      inner join base  on  base.ed31_i_codigo = atestvaga.ed102_i_base";
     $sql2 = "";
     if($dbwhere==""){
       if($ed103_i_codigo!=null ){
         $sql2 .= " where matriculaserie.ed221_c_origem = 'S' AND transfescolarede.ed103_i_codigo = $ed103_i_codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where matriculaserie.ed221_c_origem = 'S' AND $dbwhere";
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
  function sql_query_transferido( $ed103_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " FROM transfescolarede ";
    $sql .= '      inner join escola  on  escola.ed18_i_codigo = transfescolarede.ed103_i_escolaorigem ';
    $sql .= '      inner join censouf  on  censouf.ed260_i_codigo = escola.ed18_i_censouf ';
    $sql .= '      inner join censomunic  on  censomunic.ed261_i_codigo = escola.ed18_i_censomunic ';
    $sql .= '      inner join matricula  on  matricula.ed60_i_codigo = transfescolarede.ed103_i_matricula';
    $sql .= '      inner join atestvaga  on  atestvaga.ed102_i_codigo = transfescolarede.ed103_i_atestvaga ';
    $sql .= '      inner join escola  as escoladestino on  escoladestino.ed18_i_codigo = atestvaga.ed102_i_escola ';
    $sql .= '      inner join censouf as censoufdestino on  censoufdestino.ed260_i_codigo = ';
    $sql .= '                                                   escoladestino.ed18_i_censouf';
    $sql .= '      inner join censomunic as censomunicdestino  on  censomunicdestino.ed261_i_codigo = ';
    $sql .= '                                                         escoladestino.ed18_i_censomunic ';
    $sql .= '      inner join aluno  on   aluno.ed47_i_codigo = atestvaga.ed102_i_aluno ';
    $sql2 = " ";
    if($dbwhere==""){
      if($ed103_i_codigo!=null ){
        $sql2 .= " transfescolarede.ed103_i_codigo = $ed103_i_codigo ";
      }
    }else if($dbwhere != ""){
      $sql2 = "WHERE $dbwhere";
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
   function sql_query_tipotransferido( $ed103_i_codigo = null, $sCampos = "*", $sOrdem = null, $sWhere = "") {
     $sSql = "select ";
     if ($sCampos != "*" ) {
       
       $sCampos  = split("#",$sCampos);
       $sVirgula = "";
       for ($iCont = 0; $iCont < sizeof($sCampos); $iCont++){
          
          $sSql     .= $sVirgula.$sCampos[$iCont];
          $sVirgula  = ",";

       }

     } else {
        $sSql .= $sCampos;
     }

     $sSql .= " FROM transfescolarede ";
     $sSql .= "   inner join matricula on ed60_i_codigo = ed103_i_matricula ";
     $sSql .= "   inner join atestvaga on ed102_i_codigo = ed103_i_atestvaga ";
     $sSql .= "   inner join escola on ed18_i_codigo = ed103_i_escolaorigem ";
     $sSql2 = " ";
     if ($sWhere == "") {
       
       if ($ed103_i_codigo != null ) {
         $sSql2 .= " AND transfescolarede.ed103_i_codigo = $ed103_i_codigo ";
       }
     
     } else if($sWhere != "") {
        $sSql2 = " where $sWhere";
     }

     $sSql .= $sSql2;
     if ($sOrdem != null ){
        
        $sSql    .= " order by ";
        $sCampos  = split("#",$sOrdem);
        $sVirgula = "";
        for ($iCont = 0; $iCont < sizeof($sCampos); $iCont++) {
            
          $sSql     .= $virgula.$sCampos[$iCont];
          $sVirgula  = ",";
          
        }
        
     }
     
     return $sSql;
     
  }
  function sql_query_file ( $ed103_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from transfescolarede ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed103_i_codigo!=null ){
         $sql2 .= " where transfescolarede.ed103_i_codigo = $ed103_i_codigo ";
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