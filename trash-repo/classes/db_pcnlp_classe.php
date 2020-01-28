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

//MODULO: recursoshumanos
//CLASSE DA ENTIDADE pcnlp
class cl_pcnlp { 
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
   var $h09_regist = 0; 
   var $h09_porta = null; 
   var $h09_emissa_dia = null; 
   var $h09_emissa_mes = null; 
   var $h09_emissa_ano = null; 
   var $h09_emissa = null; 
   var $h09_nmes = null; 
   var $h09_proc = null; 
   var $h09_per1_dia = null; 
   var $h09_per1_mes = null; 
   var $h09_per1_ano = null; 
   var $h09_per1 = null; 
   var $h09_per2_dia = null; 
   var $h09_per2_mes = null; 
   var $h09_per2_ano = null; 
   var $h09_per2 = null; 
   var $h09_lein = null; 
   var $h09_art = null; 
   var $h09_cargo = null; 
   var $h09_cargas = null; 
   var $h09_pref = null; 
   var $h09_locpu = null; 
   var $h09_perpu = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h09_regist = int4 = Código 
                 h09_porta = varchar(9) = Número da Portaria 
                 h09_emissa = date = Data da Emissao 
                 h09_nmes = varchar(2) = Mês da emissão 
                 h09_proc = varchar(9) = Código do Processo 
                 h09_per1 = date = Periodo Inicial 
                 h09_per2 = date = Periodo Final 
                 h09_lein = varchar(9) = Lei Portaria 
                 h09_art = varchar(6) = Artigo da Lei 
                 h09_cargo = varchar(21) = Cargo 
                 h09_cargas = varchar(1) = Quem Determinou 
                 h09_pref = varchar(40) = Nome do Responsável 
                 h09_locpu = varchar(20) = Local da Publicação 
                 h09_perpu = varchar(20) = Período Publicação 
                 ";
   //funcao construtor da classe 
   function cl_pcnlp() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pcnlp"); 
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
       $this->h09_regist = ($this->h09_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["h09_regist"]:$this->h09_regist);
       $this->h09_porta = ($this->h09_porta == ""?@$GLOBALS["HTTP_POST_VARS"]["h09_porta"]:$this->h09_porta);
       if($this->h09_emissa == ""){
         $this->h09_emissa_dia = ($this->h09_emissa_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["h09_emissa_dia"]:$this->h09_emissa_dia);
         $this->h09_emissa_mes = ($this->h09_emissa_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["h09_emissa_mes"]:$this->h09_emissa_mes);
         $this->h09_emissa_ano = ($this->h09_emissa_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["h09_emissa_ano"]:$this->h09_emissa_ano);
         if($this->h09_emissa_dia != ""){
            $this->h09_emissa = $this->h09_emissa_ano."-".$this->h09_emissa_mes."-".$this->h09_emissa_dia;
         }
       }
       $this->h09_nmes = ($this->h09_nmes == ""?@$GLOBALS["HTTP_POST_VARS"]["h09_nmes"]:$this->h09_nmes);
       $this->h09_proc = ($this->h09_proc == ""?@$GLOBALS["HTTP_POST_VARS"]["h09_proc"]:$this->h09_proc);
       if($this->h09_per1 == ""){
         $this->h09_per1_dia = ($this->h09_per1_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["h09_per1_dia"]:$this->h09_per1_dia);
         $this->h09_per1_mes = ($this->h09_per1_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["h09_per1_mes"]:$this->h09_per1_mes);
         $this->h09_per1_ano = ($this->h09_per1_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["h09_per1_ano"]:$this->h09_per1_ano);
         if($this->h09_per1_dia != ""){
            $this->h09_per1 = $this->h09_per1_ano."-".$this->h09_per1_mes."-".$this->h09_per1_dia;
         }
       }
       if($this->h09_per2 == ""){
         $this->h09_per2_dia = ($this->h09_per2_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["h09_per2_dia"]:$this->h09_per2_dia);
         $this->h09_per2_mes = ($this->h09_per2_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["h09_per2_mes"]:$this->h09_per2_mes);
         $this->h09_per2_ano = ($this->h09_per2_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["h09_per2_ano"]:$this->h09_per2_ano);
         if($this->h09_per2_dia != ""){
            $this->h09_per2 = $this->h09_per2_ano."-".$this->h09_per2_mes."-".$this->h09_per2_dia;
         }
       }
       $this->h09_lein = ($this->h09_lein == ""?@$GLOBALS["HTTP_POST_VARS"]["h09_lein"]:$this->h09_lein);
       $this->h09_art = ($this->h09_art == ""?@$GLOBALS["HTTP_POST_VARS"]["h09_art"]:$this->h09_art);
       $this->h09_cargo = ($this->h09_cargo == ""?@$GLOBALS["HTTP_POST_VARS"]["h09_cargo"]:$this->h09_cargo);
       $this->h09_cargas = ($this->h09_cargas == ""?@$GLOBALS["HTTP_POST_VARS"]["h09_cargas"]:$this->h09_cargas);
       $this->h09_pref = ($this->h09_pref == ""?@$GLOBALS["HTTP_POST_VARS"]["h09_pref"]:$this->h09_pref);
       $this->h09_locpu = ($this->h09_locpu == ""?@$GLOBALS["HTTP_POST_VARS"]["h09_locpu"]:$this->h09_locpu);
       $this->h09_perpu = ($this->h09_perpu == ""?@$GLOBALS["HTTP_POST_VARS"]["h09_perpu"]:$this->h09_perpu);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->h09_regist == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "h09_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h09_porta == null ){ 
       $this->erro_sql = " Campo Número da Portaria nao Informado.";
       $this->erro_campo = "h09_porta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h09_emissa == null ){ 
       $this->erro_sql = " Campo Data da Emissao nao Informado.";
       $this->erro_campo = "h09_emissa_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h09_nmes == null ){ 
       $this->erro_sql = " Campo Mês da emissão nao Informado.";
       $this->erro_campo = "h09_nmes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h09_proc == null ){ 
       $this->erro_sql = " Campo Código do Processo nao Informado.";
       $this->erro_campo = "h09_proc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h09_per1 == null ){ 
       $this->erro_sql = " Campo Periodo Inicial nao Informado.";
       $this->erro_campo = "h09_per1_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h09_per2 == null ){ 
       $this->erro_sql = " Campo Periodo Final nao Informado.";
       $this->erro_campo = "h09_per2_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h09_lein == null ){ 
       $this->erro_sql = " Campo Lei Portaria nao Informado.";
       $this->erro_campo = "h09_lein";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h09_art == null ){ 
       $this->erro_sql = " Campo Artigo da Lei nao Informado.";
       $this->erro_campo = "h09_art";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h09_cargo == null ){ 
       $this->erro_sql = " Campo Cargo nao Informado.";
       $this->erro_campo = "h09_cargo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h09_cargas == null ){ 
       $this->erro_sql = " Campo Quem Determinou nao Informado.";
       $this->erro_campo = "h09_cargas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h09_pref == null ){ 
       $this->erro_sql = " Campo Nome do Responsável nao Informado.";
       $this->erro_campo = "h09_pref";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h09_locpu == null ){ 
       $this->erro_sql = " Campo Local da Publicação nao Informado.";
       $this->erro_campo = "h09_locpu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h09_perpu == null ){ 
       $this->erro_sql = " Campo Período Publicação nao Informado.";
       $this->erro_campo = "h09_perpu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pcnlp(
                                       h09_regist 
                                      ,h09_porta 
                                      ,h09_emissa 
                                      ,h09_nmes 
                                      ,h09_proc 
                                      ,h09_per1 
                                      ,h09_per2 
                                      ,h09_lein 
                                      ,h09_art 
                                      ,h09_cargo 
                                      ,h09_cargas 
                                      ,h09_pref 
                                      ,h09_locpu 
                                      ,h09_perpu 
                       )
                values (
                                $this->h09_regist 
                               ,'$this->h09_porta' 
                               ,".($this->h09_emissa == "null" || $this->h09_emissa == ""?"null":"'".$this->h09_emissa."'")." 
                               ,'$this->h09_nmes' 
                               ,'$this->h09_proc' 
                               ,".($this->h09_per1 == "null" || $this->h09_per1 == ""?"null":"'".$this->h09_per1."'")." 
                               ,".($this->h09_per2 == "null" || $this->h09_per2 == ""?"null":"'".$this->h09_per2."'")." 
                               ,'$this->h09_lein' 
                               ,'$this->h09_art' 
                               ,'$this->h09_cargo' 
                               ,'$this->h09_cargas' 
                               ,'$this->h09_pref' 
                               ,'$this->h09_locpu' 
                               ,'$this->h09_perpu' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastrdo de Portaria                              () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastrdo de Portaria                              já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastrdo de Portaria                              () nao Incluído. Inclusao Abortada.";
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
     $sql = " update pcnlp set ";
     $virgula = "";
     if(trim($this->h09_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h09_regist"])){ 
       $sql  .= $virgula." h09_regist = $this->h09_regist ";
       $virgula = ",";
       if(trim($this->h09_regist) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "h09_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h09_porta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h09_porta"])){ 
       $sql  .= $virgula." h09_porta = '$this->h09_porta' ";
       $virgula = ",";
       if(trim($this->h09_porta) == null ){ 
         $this->erro_sql = " Campo Número da Portaria nao Informado.";
         $this->erro_campo = "h09_porta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h09_emissa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h09_emissa_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["h09_emissa_dia"] !="") ){ 
       $sql  .= $virgula." h09_emissa = '$this->h09_emissa' ";
       $virgula = ",";
       if(trim($this->h09_emissa) == null ){ 
         $this->erro_sql = " Campo Data da Emissao nao Informado.";
         $this->erro_campo = "h09_emissa_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["h09_emissa_dia"])){ 
         $sql  .= $virgula." h09_emissa = null ";
         $virgula = ",";
         if(trim($this->h09_emissa) == null ){ 
           $this->erro_sql = " Campo Data da Emissao nao Informado.";
           $this->erro_campo = "h09_emissa_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->h09_nmes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h09_nmes"])){ 
       $sql  .= $virgula." h09_nmes = '$this->h09_nmes' ";
       $virgula = ",";
       if(trim($this->h09_nmes) == null ){ 
         $this->erro_sql = " Campo Mês da emissão nao Informado.";
         $this->erro_campo = "h09_nmes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h09_proc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h09_proc"])){ 
       $sql  .= $virgula." h09_proc = '$this->h09_proc' ";
       $virgula = ",";
       if(trim($this->h09_proc) == null ){ 
         $this->erro_sql = " Campo Código do Processo nao Informado.";
         $this->erro_campo = "h09_proc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h09_per1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h09_per1_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["h09_per1_dia"] !="") ){ 
       $sql  .= $virgula." h09_per1 = '$this->h09_per1' ";
       $virgula = ",";
       if(trim($this->h09_per1) == null ){ 
         $this->erro_sql = " Campo Periodo Inicial nao Informado.";
         $this->erro_campo = "h09_per1_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["h09_per1_dia"])){ 
         $sql  .= $virgula." h09_per1 = null ";
         $virgula = ",";
         if(trim($this->h09_per1) == null ){ 
           $this->erro_sql = " Campo Periodo Inicial nao Informado.";
           $this->erro_campo = "h09_per1_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->h09_per2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h09_per2_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["h09_per2_dia"] !="") ){ 
       $sql  .= $virgula." h09_per2 = '$this->h09_per2' ";
       $virgula = ",";
       if(trim($this->h09_per2) == null ){ 
         $this->erro_sql = " Campo Periodo Final nao Informado.";
         $this->erro_campo = "h09_per2_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["h09_per2_dia"])){ 
         $sql  .= $virgula." h09_per2 = null ";
         $virgula = ",";
         if(trim($this->h09_per2) == null ){ 
           $this->erro_sql = " Campo Periodo Final nao Informado.";
           $this->erro_campo = "h09_per2_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->h09_lein)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h09_lein"])){ 
       $sql  .= $virgula." h09_lein = '$this->h09_lein' ";
       $virgula = ",";
       if(trim($this->h09_lein) == null ){ 
         $this->erro_sql = " Campo Lei Portaria nao Informado.";
         $this->erro_campo = "h09_lein";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h09_art)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h09_art"])){ 
       $sql  .= $virgula." h09_art = '$this->h09_art' ";
       $virgula = ",";
       if(trim($this->h09_art) == null ){ 
         $this->erro_sql = " Campo Artigo da Lei nao Informado.";
         $this->erro_campo = "h09_art";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h09_cargo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h09_cargo"])){ 
       $sql  .= $virgula." h09_cargo = '$this->h09_cargo' ";
       $virgula = ",";
       if(trim($this->h09_cargo) == null ){ 
         $this->erro_sql = " Campo Cargo nao Informado.";
         $this->erro_campo = "h09_cargo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h09_cargas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h09_cargas"])){ 
       $sql  .= $virgula." h09_cargas = '$this->h09_cargas' ";
       $virgula = ",";
       if(trim($this->h09_cargas) == null ){ 
         $this->erro_sql = " Campo Quem Determinou nao Informado.";
         $this->erro_campo = "h09_cargas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h09_pref)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h09_pref"])){ 
       $sql  .= $virgula." h09_pref = '$this->h09_pref' ";
       $virgula = ",";
       if(trim($this->h09_pref) == null ){ 
         $this->erro_sql = " Campo Nome do Responsável nao Informado.";
         $this->erro_campo = "h09_pref";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h09_locpu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h09_locpu"])){ 
       $sql  .= $virgula." h09_locpu = '$this->h09_locpu' ";
       $virgula = ",";
       if(trim($this->h09_locpu) == null ){ 
         $this->erro_sql = " Campo Local da Publicação nao Informado.";
         $this->erro_campo = "h09_locpu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h09_perpu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h09_perpu"])){ 
       $sql  .= $virgula." h09_perpu = '$this->h09_perpu' ";
       $virgula = ",";
       if(trim($this->h09_perpu) == null ){ 
         $this->erro_sql = " Campo Período Publicação nao Informado.";
         $this->erro_campo = "h09_perpu";
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
       $this->erro_sql   = "Cadastrdo de Portaria                              nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastrdo de Portaria                              nao foi Alterado. Alteracao Executada.\\n";
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
     $sql = " delete from pcnlp
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
       $this->erro_sql   = "Cadastrdo de Portaria                              nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastrdo de Portaria                              nao Encontrado. Exclusão não Efetuada.\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:pcnlp";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>