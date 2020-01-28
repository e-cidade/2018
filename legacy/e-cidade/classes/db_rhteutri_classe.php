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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhteutri
class cl_rhteutri { 
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
   var $rh67_sequencial = 0; 
   var $rh67_regist = 0; 
   var $rh67_rhtipovale = 0; 
   var $rh67_cartao = null; 
   var $rh67_grupo = 0; 
   var $rh67_dias = 0; 
   var $rh67_vales = 0; 
   var $rh67_ativo = 'f'; 
   var $rh67_db_usuarios = 0; 
   var $rh67_data_dia = null; 
   var $rh67_data_mes = null; 
   var $rh67_data_ano = null; 
   var $rh67_data = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh67_sequencial = int4 = Sequencial 
                 rh67_regist = int4 = Matrícula 
                 rh67_rhtipovale = int4 = Cód.Tipo 
                 rh67_cartao = varchar(20) = Cartão 
                 rh67_grupo = int4 = Grupo 
                 rh67_dias = int4 = Dias 
                 rh67_vales = int4 = Vales/Dia 
                 rh67_ativo = bool = Ativo 
                 rh67_db_usuarios = int4 = Cod. Usuário 
                 rh67_data = date = Data 
                 ";
   //funcao construtor da classe 
   function cl_rhteutri() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhteutri"); 
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
       $this->rh67_sequencial = ($this->rh67_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh67_sequencial"]:$this->rh67_sequencial);
       $this->rh67_regist = ($this->rh67_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["rh67_regist"]:$this->rh67_regist);
       $this->rh67_rhtipovale = ($this->rh67_rhtipovale == ""?@$GLOBALS["HTTP_POST_VARS"]["rh67_rhtipovale"]:$this->rh67_rhtipovale);
       $this->rh67_cartao = ($this->rh67_cartao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh67_cartao"]:$this->rh67_cartao);
       $this->rh67_grupo = ($this->rh67_grupo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh67_grupo"]:$this->rh67_grupo);
       $this->rh67_dias = ($this->rh67_dias == ""?@$GLOBALS["HTTP_POST_VARS"]["rh67_dias"]:$this->rh67_dias);
       $this->rh67_vales = ($this->rh67_vales == ""?@$GLOBALS["HTTP_POST_VARS"]["rh67_vales"]:$this->rh67_vales);
       $this->rh67_ativo = ($this->rh67_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["rh67_ativo"]:$this->rh67_ativo);
       $this->rh67_db_usuarios = ($this->rh67_db_usuarios == ""?@$GLOBALS["HTTP_POST_VARS"]["rh67_db_usuarios"]:$this->rh67_db_usuarios);
       if($this->rh67_data == ""){
         $this->rh67_data_dia = ($this->rh67_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh67_data_dia"]:$this->rh67_data_dia);
         $this->rh67_data_mes = ($this->rh67_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh67_data_mes"]:$this->rh67_data_mes);
         $this->rh67_data_ano = ($this->rh67_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh67_data_ano"]:$this->rh67_data_ano);
         if($this->rh67_data_dia != ""){
            $this->rh67_data = $this->rh67_data_ano."-".$this->rh67_data_mes."-".$this->rh67_data_dia;
         }
       }
     }else{
       $this->rh67_sequencial = ($this->rh67_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh67_sequencial"]:$this->rh67_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh67_sequencial){ 
      $this->atualizacampos();
     if($this->rh67_regist == null ){ 
       $this->erro_sql = " Campo Matrícula nao Informado.";
       $this->erro_campo = "rh67_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh67_rhtipovale == null ){ 
       $this->erro_sql = " Campo Cód.Tipo nao Informado.";
       $this->erro_campo = "rh67_rhtipovale";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh67_cartao == null ){ 
       $this->erro_sql = " Campo Cartão nao Informado.";
       $this->erro_campo = "rh67_cartao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh67_grupo == null ){ 
       $this->erro_sql = " Campo Grupo nao Informado.";
       $this->erro_campo = "rh67_grupo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh67_dias == null ){ 
       $this->erro_sql = " Campo Dias nao Informado.";
       $this->erro_campo = "rh67_dias";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh67_vales == null ){ 
       $this->rh67_vales = "0";
     }
     if($this->rh67_ativo == null ){ 
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "rh67_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh67_db_usuarios == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "rh67_db_usuarios";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh67_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "rh67_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh67_sequencial == "" || $rh67_sequencial == null ){
       $result = db_query("select nextval('rhteutri_seq_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhteutri_seq_seq do campo: rh67_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh67_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhteutri_seq_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh67_sequencial)){
         $this->erro_sql = " Campo rh67_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh67_sequencial = $rh67_sequencial; 
       }
     }
     if(($this->rh67_sequencial == null) || ($this->rh67_sequencial == "") ){ 
       $this->erro_sql = " Campo rh67_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhteutri(
                                       rh67_sequencial 
                                      ,rh67_regist 
                                      ,rh67_rhtipovale 
                                      ,rh67_cartao 
                                      ,rh67_grupo 
                                      ,rh67_dias 
                                      ,rh67_vales 
                                      ,rh67_ativo 
                                      ,rh67_db_usuarios 
                                      ,rh67_data 
                       )
                values (
                                $this->rh67_sequencial 
                               ,$this->rh67_regist 
                               ,$this->rh67_rhtipovale 
                               ,'$this->rh67_cartao' 
                               ,$this->rh67_grupo 
                               ,$this->rh67_dias 
                               ,$this->rh67_vales 
                               ,'$this->rh67_ativo' 
                               ,$this->rh67_db_usuarios 
                               ,".($this->rh67_data == "null" || $this->rh67_data == ""?"null":"'".$this->rh67_data."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "rhteutri ($this->rh67_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "rhteutri já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "rhteutri ($this->rh67_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh67_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh67_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11104,'$this->rh67_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1915,11104,'','".AddSlashes(pg_result($resaco,0,'rh67_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1915,11105,'','".AddSlashes(pg_result($resaco,0,'rh67_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1915,11108,'','".AddSlashes(pg_result($resaco,0,'rh67_rhtipovale'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1915,11109,'','".AddSlashes(pg_result($resaco,0,'rh67_cartao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1915,11110,'','".AddSlashes(pg_result($resaco,0,'rh67_grupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1915,11111,'','".AddSlashes(pg_result($resaco,0,'rh67_dias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1915,12295,'','".AddSlashes(pg_result($resaco,0,'rh67_vales'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1915,11113,'','".AddSlashes(pg_result($resaco,0,'rh67_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1915,11112,'','".AddSlashes(pg_result($resaco,0,'rh67_db_usuarios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1915,11114,'','".AddSlashes(pg_result($resaco,0,'rh67_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh67_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhteutri set ";
     $virgula = "";
     if(trim($this->rh67_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh67_sequencial"])){ 
       $sql  .= $virgula." rh67_sequencial = $this->rh67_sequencial ";
       $virgula = ",";
       if(trim($this->rh67_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "rh67_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh67_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh67_regist"])){ 
       $sql  .= $virgula." rh67_regist = $this->rh67_regist ";
       $virgula = ",";
       if(trim($this->rh67_regist) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "rh67_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh67_rhtipovale)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh67_rhtipovale"])){ 
       $sql  .= $virgula." rh67_rhtipovale = $this->rh67_rhtipovale ";
       $virgula = ",";
       if(trim($this->rh67_rhtipovale) == null ){ 
         $this->erro_sql = " Campo Cód.Tipo nao Informado.";
         $this->erro_campo = "rh67_rhtipovale";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh67_cartao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh67_cartao"])){ 
       $sql  .= $virgula." rh67_cartao = '$this->rh67_cartao' ";
       $virgula = ",";
       if(trim($this->rh67_cartao) == null ){ 
         $this->erro_sql = " Campo Cartão nao Informado.";
         $this->erro_campo = "rh67_cartao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh67_grupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh67_grupo"])){ 
       $sql  .= $virgula." rh67_grupo = $this->rh67_grupo ";
       $virgula = ",";
       if(trim($this->rh67_grupo) == null ){ 
         $this->erro_sql = " Campo Grupo nao Informado.";
         $this->erro_campo = "rh67_grupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh67_dias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh67_dias"])){ 
       $sql  .= $virgula." rh67_dias = $this->rh67_dias ";
       $virgula = ",";
       if(trim($this->rh67_dias) == null ){ 
         $this->erro_sql = " Campo Dias nao Informado.";
         $this->erro_campo = "rh67_dias";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh67_vales)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh67_vales"])){ 
        if(trim($this->rh67_vales)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh67_vales"])){ 
           $this->rh67_vales = "0" ; 
        } 
       $sql  .= $virgula." rh67_vales = $this->rh67_vales ";
       $virgula = ",";
     }
     if(trim($this->rh67_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh67_ativo"])){ 
       $sql  .= $virgula." rh67_ativo = '$this->rh67_ativo' ";
       $virgula = ",";
       if(trim($this->rh67_ativo) == null ){ 
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "rh67_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh67_db_usuarios)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh67_db_usuarios"])){ 
       $sql  .= $virgula." rh67_db_usuarios = $this->rh67_db_usuarios ";
       $virgula = ",";
       if(trim($this->rh67_db_usuarios) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "rh67_db_usuarios";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh67_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh67_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh67_data_dia"] !="") ){ 
       $sql  .= $virgula." rh67_data = '$this->rh67_data' ";
       $virgula = ",";
       if(trim($this->rh67_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "rh67_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh67_data_dia"])){ 
         $sql  .= $virgula." rh67_data = null ";
         $virgula = ",";
         if(trim($this->rh67_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "rh67_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($rh67_sequencial!=null){
       $sql .= " rh67_sequencial = $this->rh67_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh67_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11104,'$this->rh67_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh67_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1915,11104,'".AddSlashes(pg_result($resaco,$conresaco,'rh67_sequencial'))."','$this->rh67_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh67_regist"]))
           $resac = db_query("insert into db_acount values($acount,1915,11105,'".AddSlashes(pg_result($resaco,$conresaco,'rh67_regist'))."','$this->rh67_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh67_rhtipovale"]))
           $resac = db_query("insert into db_acount values($acount,1915,11108,'".AddSlashes(pg_result($resaco,$conresaco,'rh67_rhtipovale'))."','$this->rh67_rhtipovale',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh67_cartao"]))
           $resac = db_query("insert into db_acount values($acount,1915,11109,'".AddSlashes(pg_result($resaco,$conresaco,'rh67_cartao'))."','$this->rh67_cartao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh67_grupo"]))
           $resac = db_query("insert into db_acount values($acount,1915,11110,'".AddSlashes(pg_result($resaco,$conresaco,'rh67_grupo'))."','$this->rh67_grupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh67_dias"]))
           $resac = db_query("insert into db_acount values($acount,1915,11111,'".AddSlashes(pg_result($resaco,$conresaco,'rh67_dias'))."','$this->rh67_dias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh67_vales"]))
           $resac = db_query("insert into db_acount values($acount,1915,12295,'".AddSlashes(pg_result($resaco,$conresaco,'rh67_vales'))."','$this->rh67_vales',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh67_ativo"]))
           $resac = db_query("insert into db_acount values($acount,1915,11113,'".AddSlashes(pg_result($resaco,$conresaco,'rh67_ativo'))."','$this->rh67_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh67_db_usuarios"]))
           $resac = db_query("insert into db_acount values($acount,1915,11112,'".AddSlashes(pg_result($resaco,$conresaco,'rh67_db_usuarios'))."','$this->rh67_db_usuarios',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh67_data"]))
           $resac = db_query("insert into db_acount values($acount,1915,11114,'".AddSlashes(pg_result($resaco,$conresaco,'rh67_data'))."','$this->rh67_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhteutri nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh67_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhteutri nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh67_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh67_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh67_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh67_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11104,'$rh67_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1915,11104,'','".AddSlashes(pg_result($resaco,$iresaco,'rh67_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1915,11105,'','".AddSlashes(pg_result($resaco,$iresaco,'rh67_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1915,11108,'','".AddSlashes(pg_result($resaco,$iresaco,'rh67_rhtipovale'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1915,11109,'','".AddSlashes(pg_result($resaco,$iresaco,'rh67_cartao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1915,11110,'','".AddSlashes(pg_result($resaco,$iresaco,'rh67_grupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1915,11111,'','".AddSlashes(pg_result($resaco,$iresaco,'rh67_dias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1915,12295,'','".AddSlashes(pg_result($resaco,$iresaco,'rh67_vales'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1915,11113,'','".AddSlashes(pg_result($resaco,$iresaco,'rh67_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1915,11112,'','".AddSlashes(pg_result($resaco,$iresaco,'rh67_db_usuarios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1915,11114,'','".AddSlashes(pg_result($resaco,$iresaco,'rh67_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhteutri
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh67_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh67_sequencial = $rh67_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhteutri nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh67_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhteutri nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh67_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh67_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhteutri";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $rh67_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhteutri ";
     $sql .= "      inner join db_usuarios      on  db_usuarios.id_usuario = rhteutri.rh67_db_usuarios";
     $sql .= "      inner join rhpessoal        on  rhpessoal.rh01_regist = rhteutri.rh67_regist";
     $sql .= "      inner join rhtipovale       on  rhtipovale.rh68_sequencial = rhteutri.rh67_rhtipovale";
     $sql .= "      inner join cgm              on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      inner join rhestcivil       on  rhestcivil.rh08_estciv = rhpessoal.rh01_estciv";
     $sql .= "      inner join rhraca           on  rhraca.rh18_raca = rhpessoal.rh01_raca";
     $sql .= "      inner join rhfuncao         on  rhfuncao.rh37_funcao = rhpessoal.rh01_funcao
                                               and  rh37_instit = ".db_getsession('DB_instit');
     $sql .= "      inner join rhinstrucao      on  rhinstrucao.rh21_instru = rhpessoal.rh01_instru";
     $sql .= "      inner join rhnacionalidade  on  rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion";
     $sql2 = "";
     if($dbwhere==""){
       if($rh67_sequencial!=null ){
         $sql2 .= " where rhteutri.rh67_sequencial = $rh67_sequencial "; 
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
   function sql_query_file ( $rh67_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhteutri ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh67_sequencial!=null ){
         $sql2 .= " where rhteutri.rh67_sequencial = $rh67_sequencial "; 
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