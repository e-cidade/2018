<?php
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

//MODULO: Laboratório
//CLASSE DA ENTIDADE lab_entrega
class cl_lab_entrega { 
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
   var $la31_i_codigo = 0; 
   var $la31_i_requiitem = 0; 
   var $la31_i_tipodocumento = 0; 
   var $la31_c_documento = null; 
   var $la31_i_cgs = 0; 
   var $la31_i_usuario = 0; 
   var $la31_d_data_dia = null; 
   var $la31_d_data_mes = null; 
   var $la31_d_data_ano = null; 
   var $la31_d_data = null; 
   var $la31_c_hora = null; 
   var $la31_retiradopor = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 la31_i_codigo = int4 = Código 
                 la31_i_requiitem = int4 = Requisição 
                 la31_i_tipodocumento = int4 = Tipo de Documento 
                 la31_c_documento = char(20) = Documento 
                 la31_i_cgs = int4 = Paciente 
                 la31_i_usuario = int4 = Usuário 
                 la31_d_data = date = Data 
                 la31_c_hora = char(5) = Hora 
                 la31_retiradopor = varchar(100) = Retirado por 
                 ";
   //funcao construtor da classe 
   function cl_lab_entrega() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("lab_entrega"); 
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
       $this->la31_i_codigo = ($this->la31_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la31_i_codigo"]:$this->la31_i_codigo);
       $this->la31_i_requiitem = ($this->la31_i_requiitem == ""?@$GLOBALS["HTTP_POST_VARS"]["la31_i_requiitem"]:$this->la31_i_requiitem);
       $this->la31_i_tipodocumento = ($this->la31_i_tipodocumento == ""?@$GLOBALS["HTTP_POST_VARS"]["la31_i_tipodocumento"]:$this->la31_i_tipodocumento);
       $this->la31_c_documento = ($this->la31_c_documento == ""?@$GLOBALS["HTTP_POST_VARS"]["la31_c_documento"]:$this->la31_c_documento);
       $this->la31_i_cgs = ($this->la31_i_cgs == ""?@$GLOBALS["HTTP_POST_VARS"]["la31_i_cgs"]:$this->la31_i_cgs);
       $this->la31_i_usuario = ($this->la31_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["la31_i_usuario"]:$this->la31_i_usuario);
       if($this->la31_d_data == ""){
         $this->la31_d_data_dia = ($this->la31_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["la31_d_data_dia"]:$this->la31_d_data_dia);
         $this->la31_d_data_mes = ($this->la31_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["la31_d_data_mes"]:$this->la31_d_data_mes);
         $this->la31_d_data_ano = ($this->la31_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["la31_d_data_ano"]:$this->la31_d_data_ano);
         if($this->la31_d_data_dia != ""){
            $this->la31_d_data = $this->la31_d_data_ano."-".$this->la31_d_data_mes."-".$this->la31_d_data_dia;
         }
       }
       $this->la31_c_hora = ($this->la31_c_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["la31_c_hora"]:$this->la31_c_hora);
       $this->la31_retiradopor = ($this->la31_retiradopor == ""?@$GLOBALS["HTTP_POST_VARS"]["la31_retiradopor"]:$this->la31_retiradopor);
     }else{
       $this->la31_i_codigo = ($this->la31_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la31_i_codigo"]:$this->la31_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($la31_i_codigo){ 
      $this->atualizacampos();
     if($this->la31_i_requiitem == null ){ 
       $this->erro_sql = " Campo Requisição não informado.";
       $this->erro_campo = "la31_i_requiitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la31_i_tipodocumento == null ){ 
       $this->erro_sql = " Campo Tipo de Documento não informado.";
       $this->erro_campo = "la31_i_tipodocumento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la31_c_documento == null ){ 
       $this->erro_sql = " Campo Documento não informado.";
       $this->erro_campo = "la31_c_documento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la31_i_cgs == null ){ 
       $this->erro_sql = " Campo Paciente não informado.";
       $this->erro_campo = "la31_i_cgs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la31_i_usuario == null ){ 
       $this->erro_sql = " Campo Usuário não informado.";
       $this->erro_campo = "la31_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la31_d_data == null ){ 
       $this->erro_sql = " Campo Data não informado.";
       $this->erro_campo = "la31_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la31_c_hora == null ){ 
       $this->erro_sql = " Campo Hora não informado.";
       $this->erro_campo = "la31_c_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la31_retiradopor == null ){ 
       $this->erro_sql = " Campo Retirado por não informado.";
       $this->erro_campo = "la31_retiradopor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($la31_i_codigo == "" || $la31_i_codigo == null ){
       $result = db_query("select nextval('lab_entrega_la31_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: lab_entrega_la31_i_codigo_seq do campo: la31_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->la31_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from lab_entrega_la31_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $la31_i_codigo)){
         $this->erro_sql = " Campo la31_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->la31_i_codigo = $la31_i_codigo; 
       }
     }
     if(($this->la31_i_codigo == null) || ($this->la31_i_codigo == "") ){ 
       $this->erro_sql = " Campo la31_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into lab_entrega(
                                       la31_i_codigo 
                                      ,la31_i_requiitem 
                                      ,la31_i_tipodocumento 
                                      ,la31_c_documento 
                                      ,la31_i_cgs 
                                      ,la31_i_usuario 
                                      ,la31_d_data 
                                      ,la31_c_hora 
                                      ,la31_retiradopor 
                       )
                values (
                                $this->la31_i_codigo 
                               ,$this->la31_i_requiitem 
                               ,$this->la31_i_tipodocumento 
                               ,'$this->la31_c_documento' 
                               ,$this->la31_i_cgs 
                               ,$this->la31_i_usuario 
                               ,".($this->la31_d_data == "null" || $this->la31_d_data == ""?"null":"'".$this->la31_d_data."'")." 
                               ,'$this->la31_c_hora' 
                               ,'$this->la31_retiradopor' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Entrega ($this->la31_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Entrega já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Entrega ($this->la31_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la31_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->la31_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16528,'$this->la31_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,2892,16528,'','".AddSlashes(pg_result($resaco,0,'la31_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2892,16529,'','".AddSlashes(pg_result($resaco,0,'la31_i_requiitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2892,16530,'','".AddSlashes(pg_result($resaco,0,'la31_i_tipodocumento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2892,16531,'','".AddSlashes(pg_result($resaco,0,'la31_c_documento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2892,16532,'','".AddSlashes(pg_result($resaco,0,'la31_i_cgs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2892,16533,'','".AddSlashes(pg_result($resaco,0,'la31_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2892,16534,'','".AddSlashes(pg_result($resaco,0,'la31_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2892,16535,'','".AddSlashes(pg_result($resaco,0,'la31_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2892,20643,'','".AddSlashes(pg_result($resaco,0,'la31_retiradopor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($la31_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update lab_entrega set ";
     $virgula = "";
     if(trim($this->la31_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la31_i_codigo"])){ 
       $sql  .= $virgula." la31_i_codigo = $this->la31_i_codigo ";
       $virgula = ",";
       if(trim($this->la31_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "la31_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la31_i_requiitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la31_i_requiitem"])){ 
       $sql  .= $virgula." la31_i_requiitem = $this->la31_i_requiitem ";
       $virgula = ",";
       if(trim($this->la31_i_requiitem) == null ){ 
         $this->erro_sql = " Campo Requisição não informado.";
         $this->erro_campo = "la31_i_requiitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la31_i_tipodocumento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la31_i_tipodocumento"])){ 
       $sql  .= $virgula." la31_i_tipodocumento = $this->la31_i_tipodocumento ";
       $virgula = ",";
       if(trim($this->la31_i_tipodocumento) == null ){ 
         $this->erro_sql = " Campo Tipo de Documento não informado.";
         $this->erro_campo = "la31_i_tipodocumento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la31_c_documento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la31_c_documento"])){ 
       $sql  .= $virgula." la31_c_documento = '$this->la31_c_documento' ";
       $virgula = ",";
       if(trim($this->la31_c_documento) == null ){ 
         $this->erro_sql = " Campo Documento não informado.";
         $this->erro_campo = "la31_c_documento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la31_i_cgs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la31_i_cgs"])){ 
       $sql  .= $virgula." la31_i_cgs = $this->la31_i_cgs ";
       $virgula = ",";
       if(trim($this->la31_i_cgs) == null ){ 
         $this->erro_sql = " Campo Paciente não informado.";
         $this->erro_campo = "la31_i_cgs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la31_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la31_i_usuario"])){ 
       $sql  .= $virgula." la31_i_usuario = $this->la31_i_usuario ";
       $virgula = ",";
       if(trim($this->la31_i_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário não informado.";
         $this->erro_campo = "la31_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la31_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la31_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["la31_d_data_dia"] !="") ){ 
       $sql  .= $virgula." la31_d_data = '$this->la31_d_data' ";
       $virgula = ",";
       if(trim($this->la31_d_data) == null ){ 
         $this->erro_sql = " Campo Data não informado.";
         $this->erro_campo = "la31_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["la31_d_data_dia"])){ 
         $sql  .= $virgula." la31_d_data = null ";
         $virgula = ",";
         if(trim($this->la31_d_data) == null ){ 
           $this->erro_sql = " Campo Data não informado.";
           $this->erro_campo = "la31_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->la31_c_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la31_c_hora"])){ 
       $sql  .= $virgula." la31_c_hora = '$this->la31_c_hora' ";
       $virgula = ",";
       if(trim($this->la31_c_hora) == null ){ 
         $this->erro_sql = " Campo Hora não informado.";
         $this->erro_campo = "la31_c_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la31_retiradopor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la31_retiradopor"])){ 
       $sql  .= $virgula." la31_retiradopor = '$this->la31_retiradopor' ";
       $virgula = ",";
       if(trim($this->la31_retiradopor) == null ){ 
         $this->erro_sql = " Campo Retirado por não informado.";
         $this->erro_campo = "la31_retiradopor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($la31_i_codigo!=null){
       $sql .= " la31_i_codigo = $this->la31_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->la31_i_codigo));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,16528,'$this->la31_i_codigo','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["la31_i_codigo"]) || $this->la31_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,2892,16528,'".AddSlashes(pg_result($resaco,$conresaco,'la31_i_codigo'))."','$this->la31_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["la31_i_requiitem"]) || $this->la31_i_requiitem != "")
             $resac = db_query("insert into db_acount values($acount,2892,16529,'".AddSlashes(pg_result($resaco,$conresaco,'la31_i_requiitem'))."','$this->la31_i_requiitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["la31_i_tipodocumento"]) || $this->la31_i_tipodocumento != "")
             $resac = db_query("insert into db_acount values($acount,2892,16530,'".AddSlashes(pg_result($resaco,$conresaco,'la31_i_tipodocumento'))."','$this->la31_i_tipodocumento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["la31_c_documento"]) || $this->la31_c_documento != "")
             $resac = db_query("insert into db_acount values($acount,2892,16531,'".AddSlashes(pg_result($resaco,$conresaco,'la31_c_documento'))."','$this->la31_c_documento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["la31_i_cgs"]) || $this->la31_i_cgs != "")
             $resac = db_query("insert into db_acount values($acount,2892,16532,'".AddSlashes(pg_result($resaco,$conresaco,'la31_i_cgs'))."','$this->la31_i_cgs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["la31_i_usuario"]) || $this->la31_i_usuario != "")
             $resac = db_query("insert into db_acount values($acount,2892,16533,'".AddSlashes(pg_result($resaco,$conresaco,'la31_i_usuario'))."','$this->la31_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["la31_d_data"]) || $this->la31_d_data != "")
             $resac = db_query("insert into db_acount values($acount,2892,16534,'".AddSlashes(pg_result($resaco,$conresaco,'la31_d_data'))."','$this->la31_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["la31_c_hora"]) || $this->la31_c_hora != "")
             $resac = db_query("insert into db_acount values($acount,2892,16535,'".AddSlashes(pg_result($resaco,$conresaco,'la31_c_hora'))."','$this->la31_c_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["la31_retiradopor"]) || $this->la31_retiradopor != "")
             $resac = db_query("insert into db_acount values($acount,2892,20643,'".AddSlashes(pg_result($resaco,$conresaco,'la31_retiradopor'))."','$this->la31_retiradopor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Entrega nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->la31_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Entrega nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->la31_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la31_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($la31_i_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($la31_i_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,16528,'$la31_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,2892,16528,'','".AddSlashes(pg_result($resaco,$iresaco,'la31_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2892,16529,'','".AddSlashes(pg_result($resaco,$iresaco,'la31_i_requiitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2892,16530,'','".AddSlashes(pg_result($resaco,$iresaco,'la31_i_tipodocumento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2892,16531,'','".AddSlashes(pg_result($resaco,$iresaco,'la31_c_documento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2892,16532,'','".AddSlashes(pg_result($resaco,$iresaco,'la31_i_cgs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2892,16533,'','".AddSlashes(pg_result($resaco,$iresaco,'la31_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2892,16534,'','".AddSlashes(pg_result($resaco,$iresaco,'la31_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2892,16535,'','".AddSlashes(pg_result($resaco,$iresaco,'la31_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2892,20643,'','".AddSlashes(pg_result($resaco,$iresaco,'la31_retiradopor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from lab_entrega
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($la31_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " la31_i_codigo = $la31_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Entrega nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$la31_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Entrega nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$la31_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$la31_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:lab_entrega";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $la31_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_entrega ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = lab_entrega.la31_i_usuario";
     $sql .= "      inner join lab_requiitem  on  lab_requiitem.la21_i_codigo = lab_entrega.la31_i_requiitem";
     $sql .= "      inner join lab_tipodocumento  on  lab_tipodocumento.la33_i_codigo = lab_entrega.la31_i_tipodocumento";
     $sql .= "      inner join cgs  on  cgs.z01_i_numcgs = lab_entrega.la31_i_cgs";
     $sql .= "      inner join lab_setorexame  on  lab_setorexame.la09_i_codigo = lab_requiitem.la21_i_setorexame";
     $sql .= "      inner join lab_requisicao  on  lab_requisicao.la22_i_codigo = lab_requiitem.la21_i_requisicao";
     $sql2 = "";
     if($dbwhere==""){
       if($la31_i_codigo!=null ){
         $sql2 .= " where lab_entrega.la31_i_codigo = $la31_i_codigo "; 
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
   function sql_query_file ( $la31_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_entrega ";
     $sql2 = "";
     if($dbwhere==""){
       if($la31_i_codigo!=null ){
         $sql2 .= " where lab_entrega.la31_i_codigo = $la31_i_codigo "; 
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
   function sql_query_consulta ( $la31_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_entrega ";
     $sql .= " inner join db_usuarios on db_usuarios.id_usuario = lab_entrega.la31_i_usuario"; 
     $sql .= " inner join lab_requiitem on lab_requiitem.la21_i_codigo = lab_entrega.la31_i_requiitem";
     $sql .= " inner join lab_coletaitem on lab_coletaitem.la32_i_requiitem= lab_requiitem.la21_i_codigo";
     $sql .= " inner join lab_tipodocumento on lab_tipodocumento.la33_i_codigo = lab_entrega.la31_i_tipodocumento"; 
     $sql .= " inner join cgs on cgs.z01_i_numcgs = lab_entrega.la31_i_cgs";
     $sql .= " inner join cgs_und on cgs_und.z01_i_cgsund = cgs.z01_i_numcgs";
     $sql .= " inner join lab_setorexame on lab_setorexame.la09_i_codigo = lab_requiitem.la21_i_setorexame"; 
     $sql .= " inner join lab_exame on lab_exame.la08_i_codigo = lab_setorexame.la09_i_exame";
     $sql .= " inner join lab_labsetor     on  lab_labsetor.la24_i_codigo      = lab_setorexame.la09_i_labsetor";
     $sql .= " inner join lab_setor        on  lab_setor.la23_i_codigo         = lab_labsetor.la24_i_setor";
     $sql .= " inner join lab_laboratorio  on  lab_laboratorio.la02_i_codigo   = lab_labsetor.la24_i_laboratorio";
     $sql .= " inner join lab_examematerial on lab_examematerial.la19_i_exame = lab_exame.la08_i_codigo";
     $sql .= " inner join lab_materialcoleta on lab_materialcoleta.la15_i_codigo = lab_examematerial.la19_i_materialcoleta";
     $sql .= " inner join lab_requisicao on lab_requisicao.la22_i_codigo = lab_requiitem.la21_i_requisicao";
     $sql .= " left join lab_resultado on lab_resultado.la52_i_requiitem = lab_requiitem.la21_i_codigo";  
     $sql .= " inner join cgs_und as a on   a.z01_i_cgsund = lab_requisicao.la22_i_cgs";
     
     $sql2 = "";
     if($dbwhere==""){
       if($la31_i_codigo!=null ){
         $sql2 .= " where lab_entrega.la31_i_codigo = $la31_i_codigo "; 
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