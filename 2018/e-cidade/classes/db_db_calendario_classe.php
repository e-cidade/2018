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

//MODULO: site
//CLASSE DA ENTIDADE db_calendario
class cl_db_calendario { 
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
   var $s_codigo = 0; 
   var $s_datainicio_dia = null; 
   var $s_datainicio_mes = null; 
   var $s_datainicio_ano = null; 
   var $s_datainicio = null; 
   var $s_horainicio = null; 
   var $s_datafim_dia = null; 
   var $s_datafim_mes = null; 
   var $s_datafim_ano = null; 
   var $s_datafim = null; 
   var $s_horafim = null; 
   var $s_secretaria = 0; 
   var $s_descricao = null; 
   var $s_localid = null; 
   var $s_telefone = null; 
   var $s_email = null; 
   var $s_obs = null; 
   var $s_intext = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 s_codigo = int4 = Código 
                 s_datainicio = date = Data início 
                 s_horainicio = varchar(5) = Hora início 
                 s_datafim = date = Data fim 
                 s_horafim = varchar(5) = Hora fim 
                 s_secretaria = int4 = Secretaria 
                 s_descricao = text = Descrição 
                 s_localid = varchar(100) = Localidade 
                 s_telefone = varchar(15) = Telefone 
                 s_email = varchar(50) = Email 
                 s_obs = text = Observação 
                 s_intext = bool = Intext 
                 ";
   //funcao construtor da classe 
   function cl_db_calendario() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_calendario"); 
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
       $this->s_codigo = ($this->s_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s_codigo"]:$this->s_codigo);
       if($this->s_datainicio == ""){
         $this->s_datainicio_dia = ($this->s_datainicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["s_datainicio_dia"]:$this->s_datainicio_dia);
         $this->s_datainicio_mes = ($this->s_datainicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["s_datainicio_mes"]:$this->s_datainicio_mes);
         $this->s_datainicio_ano = ($this->s_datainicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["s_datainicio_ano"]:$this->s_datainicio_ano);
         if($this->s_datainicio_dia != ""){
            $this->s_datainicio = $this->s_datainicio_ano."-".$this->s_datainicio_mes."-".$this->s_datainicio_dia;
         }
       }
       $this->s_horainicio = ($this->s_horainicio == ""?@$GLOBALS["HTTP_POST_VARS"]["s_horainicio"]:$this->s_horainicio);
       if($this->s_datafim == ""){
         $this->s_datafim_dia = ($this->s_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["s_datafim_dia"]:$this->s_datafim_dia);
         $this->s_datafim_mes = ($this->s_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["s_datafim_mes"]:$this->s_datafim_mes);
         $this->s_datafim_ano = ($this->s_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["s_datafim_ano"]:$this->s_datafim_ano);
         if($this->s_datafim_dia != ""){
            $this->s_datafim = $this->s_datafim_ano."-".$this->s_datafim_mes."-".$this->s_datafim_dia;
         }
       }
       $this->s_horafim = ($this->s_horafim == ""?@$GLOBALS["HTTP_POST_VARS"]["s_horafim"]:$this->s_horafim);
       $this->s_secretaria = ($this->s_secretaria == ""?@$GLOBALS["HTTP_POST_VARS"]["s_secretaria"]:$this->s_secretaria);
       $this->s_descricao = ($this->s_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["s_descricao"]:$this->s_descricao);
       $this->s_localid = ($this->s_localid == ""?@$GLOBALS["HTTP_POST_VARS"]["s_localid"]:$this->s_localid);
       $this->s_telefone = ($this->s_telefone == ""?@$GLOBALS["HTTP_POST_VARS"]["s_telefone"]:$this->s_telefone);
       $this->s_email = ($this->s_email == ""?@$GLOBALS["HTTP_POST_VARS"]["s_email"]:$this->s_email);
       $this->s_obs = ($this->s_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["s_obs"]:$this->s_obs);
       $this->s_intext = ($this->s_intext == "f"?@$GLOBALS["HTTP_POST_VARS"]["s_intext"]:$this->s_intext);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->s_codigo == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "s_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s_datainicio == null ){ 
       $this->erro_sql = " Campo Data início nao Informado.";
       $this->erro_campo = "s_datainicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s_horainicio == null ){ 
       $this->erro_sql = " Campo Hora início nao Informado.";
       $this->erro_campo = "s_horainicio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s_datafim == null ){ 
       $this->erro_sql = " Campo Data fim nao Informado.";
       $this->erro_campo = "s_datafim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s_horafim == null ){ 
       $this->erro_sql = " Campo Hora fim nao Informado.";
       $this->erro_campo = "s_horafim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s_secretaria == null ){ 
       $this->erro_sql = " Campo Secretaria nao Informado.";
       $this->erro_campo = "s_secretaria";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s_descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "s_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s_localid == null ){ 
       $this->erro_sql = " Campo Localidade nao Informado.";
       $this->erro_campo = "s_localid";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s_telefone == null ){ 
       $this->erro_sql = " Campo Telefone nao Informado.";
       $this->erro_campo = "s_telefone";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s_email == null ){ 
       $this->erro_sql = " Campo Email nao Informado.";
       $this->erro_campo = "s_email";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s_obs == null ){ 
       $this->erro_sql = " Campo Observação nao Informado.";
       $this->erro_campo = "s_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s_intext == null ){ 
       $this->erro_sql = " Campo Intext nao Informado.";
       $this->erro_campo = "s_intext";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_calendario(
                                       s_codigo 
                                      ,s_datainicio 
                                      ,s_horainicio 
                                      ,s_datafim 
                                      ,s_horafim 
                                      ,s_secretaria 
                                      ,s_descricao 
                                      ,s_localid 
                                      ,s_telefone 
                                      ,s_email 
                                      ,s_obs 
                                      ,s_intext 
                       )
                values (
                                $this->s_codigo 
                               ,".($this->s_datainicio == "null" || $this->s_datainicio == ""?"null":"'".$this->s_datainicio."'")." 
                               ,'$this->s_horainicio' 
                               ,".($this->s_datafim == "null" || $this->s_datafim == ""?"null":"'".$this->s_datafim."'")." 
                               ,'$this->s_horafim' 
                               ,$this->s_secretaria 
                               ,'$this->s_descricao' 
                               ,'$this->s_localid' 
                               ,'$this->s_telefone' 
                               ,'$this->s_email' 
                               ,'$this->s_obs' 
                               ,'$this->s_intext' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Calendário () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Calendário já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Calendário () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     return true;
   } 
   // funcao para alteracao
   function alterar ( $oid=null ) { 
      $this->atualizacampos();
     $sql = " update db_calendario set ";
     $virgula = "";
     if(trim($this->s_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s_codigo"])){ 
       $sql  .= $virgula." s_codigo = $this->s_codigo ";
       $virgula = ",";
       if(trim($this->s_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "s_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s_datainicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s_datainicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["s_datainicio_dia"] !="") ){ 
       $sql  .= $virgula." s_datainicio = '$this->s_datainicio' ";
       $virgula = ",";
       if(trim($this->s_datainicio) == null ){ 
         $this->erro_sql = " Campo Data início nao Informado.";
         $this->erro_campo = "s_datainicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["s_datainicio_dia"])){ 
         $sql  .= $virgula." s_datainicio = null ";
         $virgula = ",";
         if(trim($this->s_datainicio) == null ){ 
           $this->erro_sql = " Campo Data início nao Informado.";
           $this->erro_campo = "s_datainicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->s_horainicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s_horainicio"])){ 
       $sql  .= $virgula." s_horainicio = '$this->s_horainicio' ";
       $virgula = ",";
       if(trim($this->s_horainicio) == null ){ 
         $this->erro_sql = " Campo Hora início nao Informado.";
         $this->erro_campo = "s_horainicio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["s_datafim_dia"] !="") ){ 
       $sql  .= $virgula." s_datafim = '$this->s_datafim' ";
       $virgula = ",";
       if(trim($this->s_datafim) == null ){ 
         $this->erro_sql = " Campo Data fim nao Informado.";
         $this->erro_campo = "s_datafim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["s_datafim_dia"])){ 
         $sql  .= $virgula." s_datafim = null ";
         $virgula = ",";
         if(trim($this->s_datafim) == null ){ 
           $this->erro_sql = " Campo Data fim nao Informado.";
           $this->erro_campo = "s_datafim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->s_horafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s_horafim"])){ 
       $sql  .= $virgula." s_horafim = '$this->s_horafim' ";
       $virgula = ",";
       if(trim($this->s_horafim) == null ){ 
         $this->erro_sql = " Campo Hora fim nao Informado.";
         $this->erro_campo = "s_horafim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s_secretaria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s_secretaria"])){ 
       $sql  .= $virgula." s_secretaria = $this->s_secretaria ";
       $virgula = ",";
       if(trim($this->s_secretaria) == null ){ 
         $this->erro_sql = " Campo Secretaria nao Informado.";
         $this->erro_campo = "s_secretaria";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s_descricao"])){ 
       $sql  .= $virgula." s_descricao = '$this->s_descricao' ";
       $virgula = ",";
       if(trim($this->s_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "s_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s_localid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s_localid"])){ 
       $sql  .= $virgula." s_localid = '$this->s_localid' ";
       $virgula = ",";
       if(trim($this->s_localid) == null ){ 
         $this->erro_sql = " Campo Localidade nao Informado.";
         $this->erro_campo = "s_localid";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s_telefone)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s_telefone"])){ 
       $sql  .= $virgula." s_telefone = '$this->s_telefone' ";
       $virgula = ",";
       if(trim($this->s_telefone) == null ){ 
         $this->erro_sql = " Campo Telefone nao Informado.";
         $this->erro_campo = "s_telefone";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s_email)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s_email"])){ 
       $sql  .= $virgula." s_email = '$this->s_email' ";
       $virgula = ",";
       if(trim($this->s_email) == null ){ 
         $this->erro_sql = " Campo Email nao Informado.";
         $this->erro_campo = "s_email";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s_obs"])){ 
       $sql  .= $virgula." s_obs = '$this->s_obs' ";
       $virgula = ",";
       if(trim($this->s_obs) == null ){ 
         $this->erro_sql = " Campo Observação nao Informado.";
         $this->erro_campo = "s_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s_intext)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s_intext"])){ 
       $sql  .= $virgula." s_intext = '$this->s_intext' ";
       $virgula = ",";
       if(trim($this->s_intext) == null ){ 
         $this->erro_sql = " Campo Intext nao Informado.";
         $this->erro_campo = "s_intext";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
$sql .= "oid = '$oid'";     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Calendário nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Calendário nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ( $oid=null ,$dbwhere=null) { 
     $sql = " delete from db_calendario
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
       $sql2 = "oid = '$oid'";
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Calendário nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Calendário nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:db_calendario";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>