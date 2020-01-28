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
//CLASSE DA ENTIDADE alunonecessidade
class cl_alunonecessidade {
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
   var $ed214_i_codigo = 0;
   var $ed214_i_aluno = 0;
   var $ed214_i_necessidade = 0;
   var $ed214_c_principal = null;
   var $ed214_i_apoio = 0;
   var $ed214_d_data_dia = null;
   var $ed214_d_data_mes = null;
   var $ed214_d_data_ano = null;
   var $ed214_d_data = null;
   var $ed214_i_tipo = 0;
   var $ed214_i_escola = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed214_i_codigo = int8 = Código
                 ed214_i_aluno = int8 = Aluno
                 ed214_i_necessidade = int8 = Necessidade
                 ed214_c_principal = char(3) = Necessidade Maior
                 ed214_i_apoio = int4 = Apoio Pedagógico
                 ed214_d_data = date = Data
                 ed214_i_tipo = int4 = Tipo de Diagnóstico
                 ed214_i_escola = int8 = Última Alteração
                 ";
   //funcao construtor da classe
   function cl_alunonecessidade() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("alunonecessidade");
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
       $this->ed214_i_codigo = ($this->ed214_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed214_i_codigo"]:$this->ed214_i_codigo);
       $this->ed214_i_aluno = ($this->ed214_i_aluno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed214_i_aluno"]:$this->ed214_i_aluno);
       $this->ed214_i_necessidade = ($this->ed214_i_necessidade == ""?@$GLOBALS["HTTP_POST_VARS"]["ed214_i_necessidade"]:$this->ed214_i_necessidade);
       $this->ed214_c_principal = ($this->ed214_c_principal == ""?@$GLOBALS["HTTP_POST_VARS"]["ed214_c_principal"]:$this->ed214_c_principal);
       $this->ed214_i_apoio = ($this->ed214_i_apoio == ""?@$GLOBALS["HTTP_POST_VARS"]["ed214_i_apoio"]:$this->ed214_i_apoio);
       if($this->ed214_d_data == ""){
         $this->ed214_d_data_dia = ($this->ed214_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed214_d_data_dia"]:$this->ed214_d_data_dia);
         $this->ed214_d_data_mes = ($this->ed214_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed214_d_data_mes"]:$this->ed214_d_data_mes);
         $this->ed214_d_data_ano = ($this->ed214_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed214_d_data_ano"]:$this->ed214_d_data_ano);
         if($this->ed214_d_data_dia != ""){
            $this->ed214_d_data = $this->ed214_d_data_ano."-".$this->ed214_d_data_mes."-".$this->ed214_d_data_dia;
         }
       }
       $this->ed214_i_tipo = ($this->ed214_i_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed214_i_tipo"]:$this->ed214_i_tipo);
       $this->ed214_i_escola = ($this->ed214_i_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed214_i_escola"]:$this->ed214_i_escola);
     }else{
       $this->ed214_i_codigo = ($this->ed214_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed214_i_codigo"]:$this->ed214_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed214_i_codigo){
      $this->atualizacampos();
     if($this->ed214_i_aluno == null ){
       $this->erro_sql = " Campo Aluno nao Informado.";
       $this->erro_campo = "ed214_i_aluno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed214_i_necessidade == null ){
       $this->erro_sql = " Campo Necessidade nao Informado.";
       $this->erro_campo = "ed214_i_necessidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed214_c_principal == null ){
       $this->erro_sql = " Campo Necessidade Maior nao Informado.";
       $this->erro_campo = "ed214_c_principal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed214_i_apoio == null ){
       $this->erro_sql = " Campo Apoio Pedagógico nao Informado.";
       $this->erro_campo = "ed214_i_apoio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed214_d_data == null ){
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "ed214_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed214_i_tipo == null ){
       $this->erro_sql = " Campo Tipo de Diagnóstico nao Informado.";
       $this->erro_campo = "ed214_i_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed214_i_escola == null ){
       $this->ed214_i_escola = "null";
     }
     if($ed214_i_codigo == "" || $ed214_i_codigo == null ){
       $result = db_query("select nextval('alunonecessidade_ed214_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: alunonecessidade_ed214_i_codigo_seq do campo: ed214_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed214_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from alunonecessidade_ed214_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed214_i_codigo)){
         $this->erro_sql = " Campo ed214_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed214_i_codigo = $ed214_i_codigo;
       }
     }
     if(($this->ed214_i_codigo == null) || ($this->ed214_i_codigo == "") ){
       $this->erro_sql = " Campo ed214_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into alunonecessidade(
                                       ed214_i_codigo
                                      ,ed214_i_aluno
                                      ,ed214_i_necessidade
                                      ,ed214_c_principal
                                      ,ed214_i_apoio
                                      ,ed214_d_data
                                      ,ed214_i_tipo
                                      ,ed214_i_escola
                       )
                values (
                                $this->ed214_i_codigo
                               ,$this->ed214_i_aluno
                               ,$this->ed214_i_necessidade
                               ,'$this->ed214_c_principal'
                               ,$this->ed214_i_apoio
                               ,".($this->ed214_d_data == "null" || $this->ed214_d_data == ""?"null":"'".$this->ed214_d_data."'")."
                               ,$this->ed214_i_tipo
                               ,$this->ed214_i_escola
                      )";
             //die($sql);
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro das Necessidades dos Alunos ($this->ed214_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro das Necessidades dos Alunos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro das Necessidades dos Alunos ($this->ed214_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed214_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed214_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11072,'$this->ed214_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1907,11072,'','".AddSlashes(pg_result($resaco,0,'ed214_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1907,11073,'','".AddSlashes(pg_result($resaco,0,'ed214_i_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1907,11074,'','".AddSlashes(pg_result($resaco,0,'ed214_i_necessidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1907,11075,'','".AddSlashes(pg_result($resaco,0,'ed214_c_principal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1907,11299,'','".AddSlashes(pg_result($resaco,0,'ed214_i_apoio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1907,11300,'','".AddSlashes(pg_result($resaco,0,'ed214_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1907,11301,'','".AddSlashes(pg_result($resaco,0,'ed214_i_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1907,11302,'','".AddSlashes(pg_result($resaco,0,'ed214_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ed214_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update alunonecessidade set ";
     $virgula = "";
     if(trim($this->ed214_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed214_i_codigo"])){
       $sql  .= $virgula." ed214_i_codigo = $this->ed214_i_codigo ";
       $virgula = ",";
       if(trim($this->ed214_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed214_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed214_i_aluno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed214_i_aluno"])){
       $sql  .= $virgula." ed214_i_aluno = $this->ed214_i_aluno ";
       $virgula = ",";
       if(trim($this->ed214_i_aluno) == null ){
         $this->erro_sql = " Campo Aluno nao Informado.";
         $this->erro_campo = "ed214_i_aluno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed214_i_necessidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed214_i_necessidade"])){
       $sql  .= $virgula." ed214_i_necessidade = $this->ed214_i_necessidade ";
       $virgula = ",";
       if(trim($this->ed214_i_necessidade) == null ){
         $this->erro_sql = " Campo Necessidade nao Informado.";
         $this->erro_campo = "ed214_i_necessidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed214_c_principal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed214_c_principal"])){
       $sql  .= $virgula." ed214_c_principal = '$this->ed214_c_principal' ";
       $virgula = ",";
       if(trim($this->ed214_c_principal) == null ){
         $this->erro_sql = " Campo Necessidade Maior nao Informado.";
         $this->erro_campo = "ed214_c_principal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed214_i_apoio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed214_i_apoio"])){
       $sql  .= $virgula." ed214_i_apoio = $this->ed214_i_apoio ";
       $virgula = ",";
       if(trim($this->ed214_i_apoio) == null ){
         $this->erro_sql = " Campo Apoio Pedagógico nao Informado.";
         $this->erro_campo = "ed214_i_apoio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed214_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed214_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed214_d_data_dia"] !="") ){
       $sql  .= $virgula." ed214_d_data = '$this->ed214_d_data' ";
       $virgula = ",";
       if(trim($this->ed214_d_data) == null ){
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "ed214_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed214_d_data_dia"])){
         $sql  .= $virgula." ed214_d_data = null ";
         $virgula = ",";
         if(trim($this->ed214_d_data) == null ){
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "ed214_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed214_i_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed214_i_tipo"])){
       $sql  .= $virgula." ed214_i_tipo = $this->ed214_i_tipo ";
       $virgula = ",";
       if(trim($this->ed214_i_tipo) == null ){
         $this->erro_sql = " Campo Tipo de Diagnóstico nao Informado.";
         $this->erro_campo = "ed214_i_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed214_i_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed214_i_escola"])){
        if(trim($this->ed214_i_escola)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed214_i_escola"])){
           $this->ed214_i_escola = "null" ;
        }
       $sql  .= $virgula." ed214_i_escola = $this->ed214_i_escola ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed214_i_codigo!=null){
       $sql .= " ed214_i_codigo = $this->ed214_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed214_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11072,'$this->ed214_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed214_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1907,11072,'".AddSlashes(pg_result($resaco,$conresaco,'ed214_i_codigo'))."','$this->ed214_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed214_i_aluno"]))
           $resac = db_query("insert into db_acount values($acount,1907,11073,'".AddSlashes(pg_result($resaco,$conresaco,'ed214_i_aluno'))."','$this->ed214_i_aluno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed214_i_necessidade"]))
           $resac = db_query("insert into db_acount values($acount,1907,11074,'".AddSlashes(pg_result($resaco,$conresaco,'ed214_i_necessidade'))."','$this->ed214_i_necessidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed214_c_principal"]))
           $resac = db_query("insert into db_acount values($acount,1907,11075,'".AddSlashes(pg_result($resaco,$conresaco,'ed214_c_principal'))."','$this->ed214_c_principal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed214_i_apoio"]))
           $resac = db_query("insert into db_acount values($acount,1907,11299,'".AddSlashes(pg_result($resaco,$conresaco,'ed214_i_apoio'))."','$this->ed214_i_apoio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed214_d_data"]))
           $resac = db_query("insert into db_acount values($acount,1907,11300,'".AddSlashes(pg_result($resaco,$conresaco,'ed214_d_data'))."','$this->ed214_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed214_i_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1907,11301,'".AddSlashes(pg_result($resaco,$conresaco,'ed214_i_tipo'))."','$this->ed214_i_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed214_i_escola"]))
           $resac = db_query("insert into db_acount values($acount,1907,11302,'".AddSlashes(pg_result($resaco,$conresaco,'ed214_i_escola'))."','$this->ed214_i_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro das Necessidades dos Alunos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed214_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro das Necessidades dos Alunos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed214_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed214_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ed214_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed214_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11072,'$ed214_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1907,11072,'','".AddSlashes(pg_result($resaco,$iresaco,'ed214_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1907,11073,'','".AddSlashes(pg_result($resaco,$iresaco,'ed214_i_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1907,11074,'','".AddSlashes(pg_result($resaco,$iresaco,'ed214_i_necessidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1907,11075,'','".AddSlashes(pg_result($resaco,$iresaco,'ed214_c_principal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1907,11299,'','".AddSlashes(pg_result($resaco,$iresaco,'ed214_i_apoio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1907,11300,'','".AddSlashes(pg_result($resaco,$iresaco,'ed214_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1907,11301,'','".AddSlashes(pg_result($resaco,$iresaco,'ed214_i_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1907,11302,'','".AddSlashes(pg_result($resaco,$iresaco,'ed214_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from alunonecessidade
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed214_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed214_i_codigo = $ed214_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro das Necessidades dos Alunos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed214_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro das Necessidades dos Alunos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed214_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed214_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:alunonecessidade";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $ed214_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from alunonecessidade ";
     $sql .= "      left join escola  on  escola.ed18_i_codigo = alunonecessidade.ed214_i_escola";
     $sql .= "      inner join necessidade  on  necessidade.ed48_i_codigo = alunonecessidade.ed214_i_necessidade";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = alunonecessidade.ed214_i_aluno";
     $sql .= "      left join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      left join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      left join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql .= "      left join pais  on  pais.ed228_i_codigo = aluno.ed47_i_pais";
     $sql2 = "";
     if($dbwhere==""){
       if($ed214_i_codigo!=null ){
         $sql2 .= " where alunonecessidade.ed214_i_codigo = $ed214_i_codigo ";
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
   function sql_query_file ( $ed214_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from alunonecessidade ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed214_i_codigo!=null ){
         $sql2 .= " where alunonecessidade.ed214_i_codigo = $ed214_i_codigo ";
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