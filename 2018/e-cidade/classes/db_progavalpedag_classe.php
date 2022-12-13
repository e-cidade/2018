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

//MODULO: educação
//CLASSE DA ENTIDADE progavalpedag
class cl_progavalpedag { 
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
   var $ed117_i_codigo = 0; 
   var $ed117_i_progmatricula = 0; 
   var $ed117_i_questaoaval = 0; 
   var $ed117_i_opcaoquestao = 0; 
   var $ed117_i_usuario = 0; 
   var $ed117_d_data_dia = null; 
   var $ed117_d_data_mes = null; 
   var $ed117_d_data_ano = null; 
   var $ed117_d_data = null; 
   var $ed117_c_tipo = null; 
   var $ed117_i_ano = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed117_i_codigo = int8 = Código 
                 ed117_i_progmatricula = int8 = Matrícula 
                 ed117_i_questaoaval = int8 = Questão 
                 ed117_i_opcaoquestao = int8 = Resposta 
                 ed117_i_usuario = int8 = Usuário 
                 ed117_d_data = date = Data 
                 ed117_c_tipo = char(1) = Tipo de Avaliação 
                 ed117_i_ano = int4 = Ano Referente 
                 ";
   //funcao construtor da classe 
   function cl_progavalpedag() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("progavalpedag"); 
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
       $this->ed117_i_codigo = ($this->ed117_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed117_i_codigo"]:$this->ed117_i_codigo);
       $this->ed117_i_progmatricula = ($this->ed117_i_progmatricula == ""?@$GLOBALS["HTTP_POST_VARS"]["ed117_i_progmatricula"]:$this->ed117_i_progmatricula);
       $this->ed117_i_questaoaval = ($this->ed117_i_questaoaval == ""?@$GLOBALS["HTTP_POST_VARS"]["ed117_i_questaoaval"]:$this->ed117_i_questaoaval);
       $this->ed117_i_opcaoquestao = ($this->ed117_i_opcaoquestao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed117_i_opcaoquestao"]:$this->ed117_i_opcaoquestao);
       $this->ed117_i_usuario = ($this->ed117_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed117_i_usuario"]:$this->ed117_i_usuario);
       if($this->ed117_d_data == ""){
         $this->ed117_d_data_dia = ($this->ed117_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed117_d_data_dia"]:$this->ed117_d_data_dia);
         $this->ed117_d_data_mes = ($this->ed117_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed117_d_data_mes"]:$this->ed117_d_data_mes);
         $this->ed117_d_data_ano = ($this->ed117_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed117_d_data_ano"]:$this->ed117_d_data_ano);
         if($this->ed117_d_data_dia != ""){
            $this->ed117_d_data = $this->ed117_d_data_ano."-".$this->ed117_d_data_mes."-".$this->ed117_d_data_dia;
         }
       }
       $this->ed117_c_tipo = ($this->ed117_c_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed117_c_tipo"]:$this->ed117_c_tipo);
       $this->ed117_i_ano = ($this->ed117_i_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed117_i_ano"]:$this->ed117_i_ano);
     }else{
       $this->ed117_i_codigo = ($this->ed117_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed117_i_codigo"]:$this->ed117_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed117_i_codigo){ 
      $this->atualizacampos();
     if($this->ed117_i_progmatricula == null ){ 
       $this->erro_sql = " Campo Matrícula nao Informado.";
       $this->erro_campo = "ed117_i_progmatricula";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed117_i_questaoaval == null ){ 
       $this->erro_sql = " Campo Questão nao Informado.";
       $this->erro_campo = "ed117_i_questaoaval";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed117_i_opcaoquestao == null ){ 
       $this->erro_sql = " Campo Resposta nao Informado.";
       $this->erro_campo = "ed117_i_opcaoquestao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed117_i_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "ed117_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed117_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "ed117_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed117_c_tipo == null ){ 
       $this->erro_sql = " Campo Tipo de Avaliação nao Informado.";
       $this->erro_campo = "ed117_c_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed117_i_ano == null ){ 
       $this->erro_sql = " Campo Ano Referente nao Informado.";
       $this->erro_campo = "ed117_i_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed117_i_codigo == "" || $ed117_i_codigo == null ){
       $result = db_query("select nextval('progavalpedag_ed117_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: progavalpedag_ed117_i_codigo_seq do campo: ed117_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed117_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from progavalpedag_ed117_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed117_i_codigo)){
         $this->erro_sql = " Campo ed117_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed117_i_codigo = $ed117_i_codigo; 
       }
     }
     if(($this->ed117_i_codigo == null) || ($this->ed117_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed117_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into progavalpedag(
                                       ed117_i_codigo 
                                      ,ed117_i_progmatricula 
                                      ,ed117_i_questaoaval 
                                      ,ed117_i_opcaoquestao 
                                      ,ed117_i_usuario 
                                      ,ed117_d_data 
                                      ,ed117_c_tipo 
                                      ,ed117_i_ano 
                       )
                values (
                                $this->ed117_i_codigo 
                               ,$this->ed117_i_progmatricula 
                               ,$this->ed117_i_questaoaval 
                               ,$this->ed117_i_opcaoquestao 
                               ,$this->ed117_i_usuario 
                               ,".($this->ed117_d_data == "null" || $this->ed117_d_data == ""?"null":"'".$this->ed117_d_data."'")." 
                               ,'$this->ed117_c_tipo' 
                               ,$this->ed117_i_ano 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Avaliação Pedagógica do Professor ($this->ed117_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Avaliação Pedagógica do Professor já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Avaliação Pedagógica do Professor ($this->ed117_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed117_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed117_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1009156,'$this->ed117_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010180,1009156,'','".AddSlashes(pg_result($resaco,0,'ed117_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010180,1009157,'','".AddSlashes(pg_result($resaco,0,'ed117_i_progmatricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010180,1009158,'','".AddSlashes(pg_result($resaco,0,'ed117_i_questaoaval'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010180,1009159,'','".AddSlashes(pg_result($resaco,0,'ed117_i_opcaoquestao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010180,1009160,'','".AddSlashes(pg_result($resaco,0,'ed117_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010180,1009161,'','".AddSlashes(pg_result($resaco,0,'ed117_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010180,1009162,'','".AddSlashes(pg_result($resaco,0,'ed117_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010180,1009163,'','".AddSlashes(pg_result($resaco,0,'ed117_i_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed117_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update progavalpedag set ";
     $virgula = "";
     if(trim($this->ed117_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed117_i_codigo"])){ 
       $sql  .= $virgula." ed117_i_codigo = $this->ed117_i_codigo ";
       $virgula = ",";
       if(trim($this->ed117_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed117_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed117_i_progmatricula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed117_i_progmatricula"])){ 
       $sql  .= $virgula." ed117_i_progmatricula = $this->ed117_i_progmatricula ";
       $virgula = ",";
       if(trim($this->ed117_i_progmatricula) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "ed117_i_progmatricula";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed117_i_questaoaval)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed117_i_questaoaval"])){ 
       $sql  .= $virgula." ed117_i_questaoaval = $this->ed117_i_questaoaval ";
       $virgula = ",";
       if(trim($this->ed117_i_questaoaval) == null ){ 
         $this->erro_sql = " Campo Questão nao Informado.";
         $this->erro_campo = "ed117_i_questaoaval";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed117_i_opcaoquestao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed117_i_opcaoquestao"])){ 
       $sql  .= $virgula." ed117_i_opcaoquestao = $this->ed117_i_opcaoquestao ";
       $virgula = ",";
       if(trim($this->ed117_i_opcaoquestao) == null ){ 
         $this->erro_sql = " Campo Resposta nao Informado.";
         $this->erro_campo = "ed117_i_opcaoquestao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed117_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed117_i_usuario"])){ 
       $sql  .= $virgula." ed117_i_usuario = $this->ed117_i_usuario ";
       $virgula = ",";
       if(trim($this->ed117_i_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "ed117_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed117_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed117_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed117_d_data_dia"] !="") ){ 
       $sql  .= $virgula." ed117_d_data = '$this->ed117_d_data' ";
       $virgula = ",";
       if(trim($this->ed117_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "ed117_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed117_d_data_dia"])){ 
         $sql  .= $virgula." ed117_d_data = null ";
         $virgula = ",";
         if(trim($this->ed117_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "ed117_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed117_c_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed117_c_tipo"])){ 
       $sql  .= $virgula." ed117_c_tipo = '$this->ed117_c_tipo' ";
       $virgula = ",";
       if(trim($this->ed117_c_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo de Avaliação nao Informado.";
         $this->erro_campo = "ed117_c_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed117_i_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed117_i_ano"])){ 
       $sql  .= $virgula." ed117_i_ano = $this->ed117_i_ano ";
       $virgula = ",";
       if(trim($this->ed117_i_ano) == null ){ 
         $this->erro_sql = " Campo Ano Referente nao Informado.";
         $this->erro_campo = "ed117_i_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed117_i_codigo!=null){
       $sql .= " ed117_i_codigo = $this->ed117_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed117_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009156,'$this->ed117_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed117_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010180,1009156,'".AddSlashes(pg_result($resaco,$conresaco,'ed117_i_codigo'))."','$this->ed117_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed117_i_progmatricula"]))
           $resac = db_query("insert into db_acount values($acount,1010180,1009157,'".AddSlashes(pg_result($resaco,$conresaco,'ed117_i_progmatricula'))."','$this->ed117_i_progmatricula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed117_i_questaoaval"]))
           $resac = db_query("insert into db_acount values($acount,1010180,1009158,'".AddSlashes(pg_result($resaco,$conresaco,'ed117_i_questaoaval'))."','$this->ed117_i_questaoaval',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed117_i_opcaoquestao"]))
           $resac = db_query("insert into db_acount values($acount,1010180,1009159,'".AddSlashes(pg_result($resaco,$conresaco,'ed117_i_opcaoquestao'))."','$this->ed117_i_opcaoquestao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed117_i_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1010180,1009160,'".AddSlashes(pg_result($resaco,$conresaco,'ed117_i_usuario'))."','$this->ed117_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed117_d_data"]))
           $resac = db_query("insert into db_acount values($acount,1010180,1009161,'".AddSlashes(pg_result($resaco,$conresaco,'ed117_d_data'))."','$this->ed117_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed117_c_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1010180,1009162,'".AddSlashes(pg_result($resaco,$conresaco,'ed117_c_tipo'))."','$this->ed117_c_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed117_i_ano"]))
           $resac = db_query("insert into db_acount values($acount,1010180,1009163,'".AddSlashes(pg_result($resaco,$conresaco,'ed117_i_ano'))."','$this->ed117_i_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avaliação Pedagógica do Professor nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed117_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avaliação Pedagógica do Professor nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed117_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed117_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed117_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed117_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009156,'$ed117_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010180,1009156,'','".AddSlashes(pg_result($resaco,$iresaco,'ed117_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010180,1009157,'','".AddSlashes(pg_result($resaco,$iresaco,'ed117_i_progmatricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010180,1009158,'','".AddSlashes(pg_result($resaco,$iresaco,'ed117_i_questaoaval'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010180,1009159,'','".AddSlashes(pg_result($resaco,$iresaco,'ed117_i_opcaoquestao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010180,1009160,'','".AddSlashes(pg_result($resaco,$iresaco,'ed117_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010180,1009161,'','".AddSlashes(pg_result($resaco,$iresaco,'ed117_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010180,1009162,'','".AddSlashes(pg_result($resaco,$iresaco,'ed117_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010180,1009163,'','".AddSlashes(pg_result($resaco,$iresaco,'ed117_i_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from progavalpedag
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed117_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed117_i_codigo = $ed117_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avaliação Pedagógica do Professor nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed117_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avaliação Pedagógica do Professor nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed117_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed117_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:progavalpedag";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed117_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from progavalpedag ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = progavalpedag.ed117_i_usuario";
     $sql .= "      inner join progmatricula  on  progmatricula.ed112_i_codigo = progavalpedag.ed117_i_progmatricula";
     $sql .= "      inner join progclasse  on  progclasse.ed107_i_codigo = progmatricula.ed112_i_progclasse";
     $sql .= "      inner join opcaoquestao  on  opcaoquestao.ed106_i_codigo = progavalpedag.ed117_i_opcaoquestao";
     $sql .= "      inner join questaoaval  on  questaoaval.ed108_i_codigo = progavalpedag.ed117_i_questaoaval";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = progmatricula.ed112_i_rhpessoal";
     $sql .= "      inner join db_config  on  db_config.codigo = rhpessoal.rh01_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      inner join rhestcivil  on  rhestcivil.rh08_estciv = rhpessoal.rh01_estciv";
     $sql .= "      inner join rhraca  on  rhraca.rh18_raca = rhpessoal.rh01_raca";
     $sql .= "      inner join rhinstrucao  on  rhinstrucao.rh21_instru = rhpessoal.rh01_instru";
     $sql .= "      inner join rhnacionalidade  on  rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion";
     $sql2 = "";
     if($dbwhere==""){
       if($ed117_i_codigo!=null ){
         $sql2 .= " where progavalpedag.ed117_i_codigo = $ed117_i_codigo "; 
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
   function sql_query_file ( $ed117_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from progavalpedag ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed117_i_codigo!=null ){
         $sql2 .= " where progavalpedag.ed117_i_codigo = $ed117_i_codigo "; 
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