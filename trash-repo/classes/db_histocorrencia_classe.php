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

//MODULO: Arrecadacao
//CLASSE DA ENTIDADE histocorrencia
class cl_histocorrencia { 
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
   var $ar23_sequencial = 0; 
   var $ar23_id_usuario = 0; 
   var $ar23_instit = 0; 
   var $ar23_modulo = 0; 
   var $ar23_id_itensmenu = 0; 
   var $ar23_data_dia = null; 
   var $ar23_data_mes = null; 
   var $ar23_data_ano = null; 
   var $ar23_data = null; 
   var $ar23_hora = null; 
   var $ar23_tipo = 0; 
   var $ar23_descricao = null; 
   var $ar23_ocorrencia = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ar23_sequencial = int4 = Código Histórico 
                 ar23_id_usuario = int4 = Cod. Usuário 
                 ar23_instit = int4 = Cod. Instituição 
                 ar23_modulo = int4 = Codigo do Módulo 
                 ar23_id_itensmenu = int4 = Código do ítem 
                 ar23_data = date = Data 
                 ar23_hora = varchar(5) = Hora 
                 ar23_tipo = int4 = Tipo Registro 
                 ar23_descricao = varchar(100) = Descrição da Ocorrência 
                 ar23_ocorrencia = text = Ocorrência 
                 ";
   //funcao construtor da classe 
   function cl_histocorrencia() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("histocorrencia"); 
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
       $this->ar23_sequencial = ($this->ar23_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ar23_sequencial"]:$this->ar23_sequencial);
       $this->ar23_id_usuario = ($this->ar23_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ar23_id_usuario"]:$this->ar23_id_usuario);
       $this->ar23_instit = ($this->ar23_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["ar23_instit"]:$this->ar23_instit);
       $this->ar23_modulo = ($this->ar23_modulo == ""?@$GLOBALS["HTTP_POST_VARS"]["ar23_modulo"]:$this->ar23_modulo);
       $this->ar23_id_itensmenu = ($this->ar23_id_itensmenu == ""?@$GLOBALS["HTTP_POST_VARS"]["ar23_id_itensmenu"]:$this->ar23_id_itensmenu);
       if($this->ar23_data == ""){
         $this->ar23_data_dia = ($this->ar23_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ar23_data_dia"]:$this->ar23_data_dia);
         $this->ar23_data_mes = ($this->ar23_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ar23_data_mes"]:$this->ar23_data_mes);
         $this->ar23_data_ano = ($this->ar23_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ar23_data_ano"]:$this->ar23_data_ano);
         if($this->ar23_data_dia != ""){
            $this->ar23_data = $this->ar23_data_ano."-".$this->ar23_data_mes."-".$this->ar23_data_dia;
         }
       }
       $this->ar23_hora = ($this->ar23_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["ar23_hora"]:$this->ar23_hora);
       $this->ar23_tipo = ($this->ar23_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ar23_tipo"]:$this->ar23_tipo);
       $this->ar23_descricao = ($this->ar23_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["ar23_descricao"]:$this->ar23_descricao);
       $this->ar23_ocorrencia = ($this->ar23_ocorrencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ar23_ocorrencia"]:$this->ar23_ocorrencia);
     }else{
       $this->ar23_sequencial = ($this->ar23_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ar23_sequencial"]:$this->ar23_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ar23_sequencial){ 
      $this->atualizacampos();
     if($this->ar23_id_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "ar23_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar23_instit == null ){ 
       $this->erro_sql = " Campo Cod. Instituição nao Informado.";
       $this->erro_campo = "ar23_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar23_modulo == null ){ 
       $this->erro_sql = " Campo Codigo do Módulo nao Informado.";
       $this->erro_campo = "ar23_modulo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar23_id_itensmenu == null ){ 
       $this->erro_sql = " Campo Código do ítem nao Informado.";
       $this->erro_campo = "ar23_id_itensmenu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar23_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "ar23_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar23_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "ar23_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar23_tipo == null ){ 
       $this->erro_sql = " Campo Tipo Registro nao Informado.";
       $this->erro_campo = "ar23_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar23_descricao == null ){ 
       $this->erro_sql = " Campo Descrição da Ocorrência nao Informado.";
       $this->erro_campo = "ar23_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar23_ocorrencia == null ){ 
       $this->erro_sql = " Campo Ocorrência nao Informado.";
       $this->erro_campo = "ar23_ocorrencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ar23_sequencial == "" || $ar23_sequencial == null ){
       $result = db_query("select nextval('histocorrencia_ar23_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: histocorrencia_ar23_sequencial_seq do campo: ar23_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ar23_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from histocorrencia_ar23_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ar23_sequencial)){
         $this->erro_sql = " Campo ar23_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ar23_sequencial = $ar23_sequencial; 
       }
     }
     if(($this->ar23_sequencial == null) || ($this->ar23_sequencial == "") ){ 
       $this->erro_sql = " Campo ar23_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into histocorrencia(
                                       ar23_sequencial 
                                      ,ar23_id_usuario 
                                      ,ar23_instit 
                                      ,ar23_modulo 
                                      ,ar23_id_itensmenu 
                                      ,ar23_data 
                                      ,ar23_hora 
                                      ,ar23_tipo 
                                      ,ar23_descricao 
                                      ,ar23_ocorrencia 
                       )
                values (
                                $this->ar23_sequencial 
                               ,$this->ar23_id_usuario 
                               ,$this->ar23_instit 
                               ,$this->ar23_modulo 
                               ,$this->ar23_id_itensmenu 
                               ,".($this->ar23_data == "null" || $this->ar23_data == ""?"null":"'".$this->ar23_data."'")." 
                               ,'$this->ar23_hora' 
                               ,$this->ar23_tipo 
                               ,'$this->ar23_descricao' 
                               ,'$this->ar23_ocorrencia' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "histocorrencia ($this->ar23_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "histocorrencia já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "histocorrencia ($this->ar23_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ar23_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ar23_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15074,'$this->ar23_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2651,15074,'','".AddSlashes(pg_result($resaco,0,'ar23_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2651,15075,'','".AddSlashes(pg_result($resaco,0,'ar23_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2651,15079,'','".AddSlashes(pg_result($resaco,0,'ar23_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2651,15086,'','".AddSlashes(pg_result($resaco,0,'ar23_modulo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2651,15087,'','".AddSlashes(pg_result($resaco,0,'ar23_id_itensmenu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2651,15088,'','".AddSlashes(pg_result($resaco,0,'ar23_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2651,15089,'','".AddSlashes(pg_result($resaco,0,'ar23_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2651,15090,'','".AddSlashes(pg_result($resaco,0,'ar23_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2651,15091,'','".AddSlashes(pg_result($resaco,0,'ar23_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2651,15092,'','".AddSlashes(pg_result($resaco,0,'ar23_ocorrencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ar23_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update histocorrencia set ";
     $virgula = "";
     if(trim($this->ar23_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar23_sequencial"])){ 
       $sql  .= $virgula." ar23_sequencial = $this->ar23_sequencial ";
       $virgula = ",";
       if(trim($this->ar23_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Histórico nao Informado.";
         $this->erro_campo = "ar23_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar23_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar23_id_usuario"])){ 
       $sql  .= $virgula." ar23_id_usuario = $this->ar23_id_usuario ";
       $virgula = ",";
       if(trim($this->ar23_id_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "ar23_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar23_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar23_instit"])){ 
       $sql  .= $virgula." ar23_instit = $this->ar23_instit ";
       $virgula = ",";
       if(trim($this->ar23_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "ar23_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar23_modulo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar23_modulo"])){ 
       $sql  .= $virgula." ar23_modulo = $this->ar23_modulo ";
       $virgula = ",";
       if(trim($this->ar23_modulo) == null ){ 
         $this->erro_sql = " Campo Codigo do Módulo nao Informado.";
         $this->erro_campo = "ar23_modulo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar23_id_itensmenu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar23_id_itensmenu"])){ 
       $sql  .= $virgula." ar23_id_itensmenu = $this->ar23_id_itensmenu ";
       $virgula = ",";
       if(trim($this->ar23_id_itensmenu) == null ){ 
         $this->erro_sql = " Campo Código do ítem nao Informado.";
         $this->erro_campo = "ar23_id_itensmenu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar23_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar23_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ar23_data_dia"] !="") ){ 
       $sql  .= $virgula." ar23_data = '$this->ar23_data' ";
       $virgula = ",";
       if(trim($this->ar23_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "ar23_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ar23_data_dia"])){ 
         $sql  .= $virgula." ar23_data = null ";
         $virgula = ",";
         if(trim($this->ar23_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "ar23_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ar23_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar23_hora"])){ 
       $sql  .= $virgula." ar23_hora = '$this->ar23_hora' ";
       $virgula = ",";
       if(trim($this->ar23_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "ar23_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar23_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar23_tipo"])){ 
       $sql  .= $virgula." ar23_tipo = $this->ar23_tipo ";
       $virgula = ",";
       if(trim($this->ar23_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo Registro nao Informado.";
         $this->erro_campo = "ar23_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar23_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar23_descricao"])){ 
       $sql  .= $virgula." ar23_descricao = '$this->ar23_descricao' ";
       $virgula = ",";
       if(trim($this->ar23_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição da Ocorrência nao Informado.";
         $this->erro_campo = "ar23_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar23_ocorrencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar23_ocorrencia"])){ 
       $sql  .= $virgula." ar23_ocorrencia = '$this->ar23_ocorrencia' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ar23_sequencial!=null){
       $sql .= " ar23_sequencial = $this->ar23_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ar23_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15074,'$this->ar23_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar23_sequencial"]) || $this->ar23_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2651,15074,'".AddSlashes(pg_result($resaco,$conresaco,'ar23_sequencial'))."','$this->ar23_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar23_id_usuario"]) || $this->ar23_id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,2651,15075,'".AddSlashes(pg_result($resaco,$conresaco,'ar23_id_usuario'))."','$this->ar23_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar23_instit"]) || $this->ar23_instit != "")
           $resac = db_query("insert into db_acount values($acount,2651,15079,'".AddSlashes(pg_result($resaco,$conresaco,'ar23_instit'))."','$this->ar23_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar23_modulo"]) || $this->ar23_modulo != "")
           $resac = db_query("insert into db_acount values($acount,2651,15086,'".AddSlashes(pg_result($resaco,$conresaco,'ar23_modulo'))."','$this->ar23_modulo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar23_id_itensmenu"]) || $this->ar23_id_itensmenu != "")
           $resac = db_query("insert into db_acount values($acount,2651,15087,'".AddSlashes(pg_result($resaco,$conresaco,'ar23_id_itensmenu'))."','$this->ar23_id_itensmenu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar23_data"]) || $this->ar23_data != "")
           $resac = db_query("insert into db_acount values($acount,2651,15088,'".AddSlashes(pg_result($resaco,$conresaco,'ar23_data'))."','$this->ar23_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar23_hora"]) || $this->ar23_hora != "")
           $resac = db_query("insert into db_acount values($acount,2651,15089,'".AddSlashes(pg_result($resaco,$conresaco,'ar23_hora'))."','$this->ar23_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar23_tipo"]) || $this->ar23_tipo != "")
           $resac = db_query("insert into db_acount values($acount,2651,15090,'".AddSlashes(pg_result($resaco,$conresaco,'ar23_tipo'))."','$this->ar23_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar23_descricao"]) || $this->ar23_descricao != "")
           $resac = db_query("insert into db_acount values($acount,2651,15091,'".AddSlashes(pg_result($resaco,$conresaco,'ar23_descricao'))."','$this->ar23_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar23_ocorrencia"]) || $this->ar23_ocorrencia != "")
           $resac = db_query("insert into db_acount values($acount,2651,15092,'".AddSlashes(pg_result($resaco,$conresaco,'ar23_ocorrencia'))."','$this->ar23_ocorrencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "histocorrencia nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ar23_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "histocorrencia nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ar23_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ar23_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ar23_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ar23_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15074,'$ar23_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2651,15074,'','".AddSlashes(pg_result($resaco,$iresaco,'ar23_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2651,15075,'','".AddSlashes(pg_result($resaco,$iresaco,'ar23_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2651,15079,'','".AddSlashes(pg_result($resaco,$iresaco,'ar23_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2651,15086,'','".AddSlashes(pg_result($resaco,$iresaco,'ar23_modulo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2651,15087,'','".AddSlashes(pg_result($resaco,$iresaco,'ar23_id_itensmenu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2651,15088,'','".AddSlashes(pg_result($resaco,$iresaco,'ar23_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2651,15089,'','".AddSlashes(pg_result($resaco,$iresaco,'ar23_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2651,15090,'','".AddSlashes(pg_result($resaco,$iresaco,'ar23_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2651,15091,'','".AddSlashes(pg_result($resaco,$iresaco,'ar23_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2651,15092,'','".AddSlashes(pg_result($resaco,$iresaco,'ar23_ocorrencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from histocorrencia
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ar23_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ar23_sequencial = $ar23_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "histocorrencia nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ar23_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "histocorrencia nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ar23_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ar23_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:histocorrencia";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ar23_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from histocorrencia ";
     $sql .= "      inner join db_config  on  db_config.codigo = histocorrencia.ar23_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = histocorrencia.ar23_id_usuario";
     $sql .= "      inner join db_itensmenu  on  db_itensmenu.id_item = histocorrencia.ar23_id_itensmenu";
     $sql .= "      inner join db_modulos  on  db_modulos.id_item = histocorrencia.ar23_modulo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($ar23_sequencial!=null ){
         $sql2 .= " where histocorrencia.ar23_sequencial = $ar23_sequencial "; 
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
   function sql_query_file ( $ar23_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from histocorrencia ";
     $sql2 = "";
     if($dbwhere==""){
       if($ar23_sequencial!=null ){
         $sql2 .= " where histocorrencia.ar23_sequencial = $ar23_sequencial "; 
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