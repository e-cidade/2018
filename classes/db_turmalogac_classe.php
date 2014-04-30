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
//CLASSE DA ENTIDADE turmalogac
class cl_turmalogac { 
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
   var $ed288_i_codigo        = 0; 
   var $ed288_i_usuario        = 0; 
   var $ed288_i_escola        = 0; 
   var $ed288_d_data_dia    = null; 
   var $ed288_d_data_mes    = null; 
   var $ed288_d_data_ano    = null; 
   var $ed288_d_data        = null; 
   var $ed288_c_hora        = null; 
   var $ed288_i_tipoturma        = 0; 
   var $ed288_i_turmaac        = 0; 
   var $ed288_i_codigoant        = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed288_i_codigo = int4 = Código 
                 ed288_i_usuario = int4 = Usuário 
                 ed288_i_escola = int4 = Escola 
                 ed288_d_data = date = Data 
                 ed288_c_hora = char(5) = Hora 
                 ed288_i_tipoturma = int4 = Tipo Turma 
                 ed288_i_turmaac = int4 = Turmaac 
                 ed288_i_codigoant = int4 = Código Anterioor 
                 ";
   //funcao construtor da classe 
   function cl_turmalogac() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("turmalogac"); 
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
       $this->ed288_i_codigo = ($this->ed288_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed288_i_codigo"]:$this->ed288_i_codigo);
       $this->ed288_i_usuario = ($this->ed288_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed288_i_usuario"]:$this->ed288_i_usuario);
       $this->ed288_i_escola = ($this->ed288_i_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed288_i_escola"]:$this->ed288_i_escola);
       if($this->ed288_d_data == ""){
         $this->ed288_d_data_dia = ($this->ed288_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed288_d_data_dia"]:$this->ed288_d_data_dia);
         $this->ed288_d_data_mes = ($this->ed288_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed288_d_data_mes"]:$this->ed288_d_data_mes);
         $this->ed288_d_data_ano = ($this->ed288_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed288_d_data_ano"]:$this->ed288_d_data_ano);
         if($this->ed288_d_data_dia != ""){
            $this->ed288_d_data = $this->ed288_d_data_ano."-".$this->ed288_d_data_mes."-".$this->ed288_d_data_dia;
         }
       }
       $this->ed288_c_hora = ($this->ed288_c_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["ed288_c_hora"]:$this->ed288_c_hora);
       $this->ed288_i_tipoturma = ($this->ed288_i_tipoturma == ""?@$GLOBALS["HTTP_POST_VARS"]["ed288_i_tipoturma"]:$this->ed288_i_tipoturma);
       $this->ed288_i_turmaac = ($this->ed288_i_turmaac == ""?@$GLOBALS["HTTP_POST_VARS"]["ed288_i_turmaac"]:$this->ed288_i_turmaac);
       $this->ed288_i_codigoant = ($this->ed288_i_codigoant == ""?@$GLOBALS["HTTP_POST_VARS"]["ed288_i_codigoant"]:$this->ed288_i_codigoant);
     }else{
       $this->ed288_i_codigo = ($this->ed288_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed288_i_codigo"]:$this->ed288_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed288_i_codigo){ 
      $this->atualizacampos();
     if($this->ed288_i_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "ed288_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed288_i_escola == null ){ 
       $this->erro_sql = " Campo Escola nao Informado.";
       $this->erro_campo = "ed288_i_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed288_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "ed288_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed288_c_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "ed288_c_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed288_i_tipoturma == null ){ 
       $this->erro_sql = " Campo Tipo Turma nao Informado.";
       $this->erro_campo = "ed288_i_tipoturma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed288_i_turmaac == null ){ 
       $this->erro_sql = " Campo Turmaac nao Informado.";
       $this->erro_campo = "ed288_i_turmaac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed288_i_codigoant == null ){ 
       $this->erro_sql = " Campo Código Anterioor nao Informado.";
       $this->erro_campo = "ed288_i_codigoant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed288_i_codigo == "" || $ed288_i_codigo == null ){
       $result = db_query("select nextval('turmalogac_ed288_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: turmalogac_ed288_i_codigo_seq do campo: ed288_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed288_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from turmalogac_ed288_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed288_i_codigo)){
         $this->erro_sql = " Campo ed288_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed288_i_codigo = $ed288_i_codigo; 
       }
     }
     if(($this->ed288_i_codigo == null) || ($this->ed288_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed288_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into turmalogac(
                                       ed288_i_codigo 
                                      ,ed288_i_usuario 
                                      ,ed288_i_escola 
                                      ,ed288_d_data 
                                      ,ed288_c_hora 
                                      ,ed288_i_tipoturma 
                                      ,ed288_i_turmaac 
                                      ,ed288_i_codigoant 
                       )
                values (
                                $this->ed288_i_codigo 
                               ,$this->ed288_i_usuario 
                               ,$this->ed288_i_escola 
                               ,".($this->ed288_d_data == "null" || $this->ed288_d_data == ""?"null":"'".$this->ed288_d_data."'")." 
                               ,'$this->ed288_c_hora' 
                               ,$this->ed288_i_tipoturma 
                               ,$this->ed288_i_turmaac 
                               ,$this->ed288_i_codigoant 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "turmalogac ($this->ed288_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "turmalogac já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "turmalogac ($this->ed288_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed288_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed288_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17614,'$this->ed288_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,3111,17614,'','".AddSlashes(pg_result($resaco,0,'ed288_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3111,17615,'','".AddSlashes(pg_result($resaco,0,'ed288_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3111,17616,'','".AddSlashes(pg_result($resaco,0,'ed288_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3111,17617,'','".AddSlashes(pg_result($resaco,0,'ed288_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3111,17618,'','".AddSlashes(pg_result($resaco,0,'ed288_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3111,17619,'','".AddSlashes(pg_result($resaco,0,'ed288_i_tipoturma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3111,17620,'','".AddSlashes(pg_result($resaco,0,'ed288_i_turmaac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3111,17655,'','".AddSlashes(pg_result($resaco,0,'ed288_i_codigoant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed288_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update turmalogac set ";
     $virgula = "";
     if(trim($this->ed288_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed288_i_codigo"])){ 
       $sql  .= $virgula." ed288_i_codigo = $this->ed288_i_codigo ";
       $virgula = ",";
       if(trim($this->ed288_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed288_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed288_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed288_i_usuario"])){ 
       $sql  .= $virgula." ed288_i_usuario = $this->ed288_i_usuario ";
       $virgula = ",";
       if(trim($this->ed288_i_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "ed288_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed288_i_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed288_i_escola"])){ 
       $sql  .= $virgula." ed288_i_escola = $this->ed288_i_escola ";
       $virgula = ",";
       if(trim($this->ed288_i_escola) == null ){ 
         $this->erro_sql = " Campo Escola nao Informado.";
         $this->erro_campo = "ed288_i_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed288_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed288_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed288_d_data_dia"] !="") ){ 
       $sql  .= $virgula." ed288_d_data = '$this->ed288_d_data' ";
       $virgula = ",";
       if(trim($this->ed288_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "ed288_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed288_d_data_dia"])){ 
         $sql  .= $virgula." ed288_d_data = null ";
         $virgula = ",";
         if(trim($this->ed288_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "ed288_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed288_c_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed288_c_hora"])){ 
       $sql  .= $virgula." ed288_c_hora = '$this->ed288_c_hora' ";
       $virgula = ",";
       if(trim($this->ed288_c_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "ed288_c_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed288_i_tipoturma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed288_i_tipoturma"])){ 
       $sql  .= $virgula." ed288_i_tipoturma = $this->ed288_i_tipoturma ";
       $virgula = ",";
       if(trim($this->ed288_i_tipoturma) == null ){ 
         $this->erro_sql = " Campo Tipo Turma nao Informado.";
         $this->erro_campo = "ed288_i_tipoturma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed288_i_turmaac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed288_i_turmaac"])){ 
       $sql  .= $virgula." ed288_i_turmaac = $this->ed288_i_turmaac ";
       $virgula = ",";
       if(trim($this->ed288_i_turmaac) == null ){ 
         $this->erro_sql = " Campo Turmaac nao Informado.";
         $this->erro_campo = "ed288_i_turmaac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed288_i_codigoant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed288_i_codigoant"])){ 
       $sql  .= $virgula." ed288_i_codigoant = $this->ed288_i_codigoant ";
       $virgula = ",";
       if(trim($this->ed288_i_codigoant) == null ){ 
         $this->erro_sql = " Campo Código Anterioor nao Informado.";
         $this->erro_campo = "ed288_i_codigoant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed288_i_codigo!=null){
       $sql .= " ed288_i_codigo = $this->ed288_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed288_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17614,'$this->ed288_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed288_i_codigo"]) || $this->ed288_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,3111,17614,'".AddSlashes(pg_result($resaco,$conresaco,'ed288_i_codigo'))."','$this->ed288_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed288_i_usuario"]) || $this->ed288_i_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3111,17615,'".AddSlashes(pg_result($resaco,$conresaco,'ed288_i_usuario'))."','$this->ed288_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed288_i_escola"]) || $this->ed288_i_escola != "")
           $resac = db_query("insert into db_acount values($acount,3111,17616,'".AddSlashes(pg_result($resaco,$conresaco,'ed288_i_escola'))."','$this->ed288_i_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed288_d_data"]) || $this->ed288_d_data != "")
           $resac = db_query("insert into db_acount values($acount,3111,17617,'".AddSlashes(pg_result($resaco,$conresaco,'ed288_d_data'))."','$this->ed288_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed288_c_hora"]) || $this->ed288_c_hora != "")
           $resac = db_query("insert into db_acount values($acount,3111,17618,'".AddSlashes(pg_result($resaco,$conresaco,'ed288_c_hora'))."','$this->ed288_c_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed288_i_tipoturma"]) || $this->ed288_i_tipoturma != "")
           $resac = db_query("insert into db_acount values($acount,3111,17619,'".AddSlashes(pg_result($resaco,$conresaco,'ed288_i_tipoturma'))."','$this->ed288_i_tipoturma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed288_i_turmaac"]) || $this->ed288_i_turmaac != "")
           $resac = db_query("insert into db_acount values($acount,3111,17620,'".AddSlashes(pg_result($resaco,$conresaco,'ed288_i_turmaac'))."','$this->ed288_i_turmaac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed288_i_codigoant"]) || $this->ed288_i_codigoant != "")
           $resac = db_query("insert into db_acount values($acount,3111,17655,'".AddSlashes(pg_result($resaco,$conresaco,'ed288_i_codigoant'))."','$this->ed288_i_codigoant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "turmalogac nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed288_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "turmalogac nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed288_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed288_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed288_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed288_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17614,'$ed288_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,3111,17614,'','".AddSlashes(pg_result($resaco,$iresaco,'ed288_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3111,17615,'','".AddSlashes(pg_result($resaco,$iresaco,'ed288_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3111,17616,'','".AddSlashes(pg_result($resaco,$iresaco,'ed288_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3111,17617,'','".AddSlashes(pg_result($resaco,$iresaco,'ed288_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3111,17618,'','".AddSlashes(pg_result($resaco,$iresaco,'ed288_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3111,17619,'','".AddSlashes(pg_result($resaco,$iresaco,'ed288_i_tipoturma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3111,17620,'','".AddSlashes(pg_result($resaco,$iresaco,'ed288_i_turmaac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3111,17655,'','".AddSlashes(pg_result($resaco,$iresaco,'ed288_i_codigoant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from turmalogac
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed288_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed288_i_codigo = $ed288_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "turmalogac nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed288_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "turmalogac nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed288_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed288_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:turmalogac";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed288_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from turmalogac ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = turmalogac.ed288_i_usuario";
     $sql .= "      inner join turmaac  on  turmaac.ed268_i_codigo = turmalogac.ed288_i_turmaac";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = turmalogac.ed288_i_escola";     
     $sql2 = "";
     if($dbwhere==""){
       if($ed288_i_codigo!=null ){
         $sql2 .= " where turmalogac.ed288_i_codigo = $ed288_i_codigo "; 
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
   function sql_query_file ( $ed288_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from turmalogac ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed288_i_codigo!=null ){
         $sql2 .= " where turmalogac.ed288_i_codigo = $ed288_i_codigo "; 
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