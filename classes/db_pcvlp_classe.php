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
//CLASSE DA ENTIDADE pcvlp
class cl_pcvlp { 
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
   var $h10_regist = 0; 
   var $h10_emissa_dia = null; 
   var $h10_emissa_mes = null; 
   var $h10_emissa_ano = null; 
   var $h10_emissa = null; 
   var $h10_porta = null; 
   var $h10_pref = null; 
   var $h10_cargas = null; 
   var $h10_proc = null; 
   var $h10_cargo = null; 
   var $h10_nmes = null; 
   var $h10_per1_dia = null; 
   var $h10_per1_mes = null; 
   var $h10_per1_ano = null; 
   var $h10_per1 = null; 
   var $h10_per2_dia = null; 
   var $h10_per2_mes = null; 
   var $h10_per2_ano = null; 
   var $h10_per2 = null; 
   var $h10_art = null; 
   var $h10_lein = null; 
   var $h10_locpu = null; 
   var $h10_perpu = null; 
   var $h10_portc = null; 
   var $h10_ndias = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h10_regist = int4 = Codigo do Funcionario 
                 h10_emissa = date = Data da Emissao 
                 h10_porta = varchar(9) = Número da Portaria 
                 h10_pref = varchar(40) = Nome do Responsável 
                 h10_cargas = varchar(1) = Quem Determinou 
                 h10_proc = varchar(9) = Código do Processo 
                 h10_cargo = varchar(21) = Cargo do Servidor 
                 h10_nmes = char(     2) = mes da Emissao 
                 h10_per1 = date = Periodo Inicial 
                 h10_per2 = date = Periodo Final 
                 h10_art = char(     6) = Artigo da Lei 
                 h10_lein = char(     9) = Lei Portaria 
                 h10_locpu = char(    20) = Local da Publicacao 
                 h10_perpu = varchar(20) = Período Publicação 
                 h10_portc = varchar(9) = Portaria de Consseção 
                 h10_ndias = char(     3) = nr dias a convertidos 
                 ";
   //funcao construtor da classe 
   function cl_pcvlp() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pcvlp"); 
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
       $this->h10_regist = ($this->h10_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["h10_regist"]:$this->h10_regist);
       if($this->h10_emissa == ""){
         $this->h10_emissa_dia = ($this->h10_emissa_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["h10_emissa_dia"]:$this->h10_emissa_dia);
         $this->h10_emissa_mes = ($this->h10_emissa_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["h10_emissa_mes"]:$this->h10_emissa_mes);
         $this->h10_emissa_ano = ($this->h10_emissa_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["h10_emissa_ano"]:$this->h10_emissa_ano);
         if($this->h10_emissa_dia != ""){
            $this->h10_emissa = $this->h10_emissa_ano."-".$this->h10_emissa_mes."-".$this->h10_emissa_dia;
         }
       }
       $this->h10_porta = ($this->h10_porta == ""?@$GLOBALS["HTTP_POST_VARS"]["h10_porta"]:$this->h10_porta);
       $this->h10_pref = ($this->h10_pref == ""?@$GLOBALS["HTTP_POST_VARS"]["h10_pref"]:$this->h10_pref);
       $this->h10_cargas = ($this->h10_cargas == ""?@$GLOBALS["HTTP_POST_VARS"]["h10_cargas"]:$this->h10_cargas);
       $this->h10_proc = ($this->h10_proc == ""?@$GLOBALS["HTTP_POST_VARS"]["h10_proc"]:$this->h10_proc);
       $this->h10_cargo = ($this->h10_cargo == ""?@$GLOBALS["HTTP_POST_VARS"]["h10_cargo"]:$this->h10_cargo);
       $this->h10_nmes = ($this->h10_nmes == ""?@$GLOBALS["HTTP_POST_VARS"]["h10_nmes"]:$this->h10_nmes);
       if($this->h10_per1 == ""){
         $this->h10_per1_dia = ($this->h10_per1_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["h10_per1_dia"]:$this->h10_per1_dia);
         $this->h10_per1_mes = ($this->h10_per1_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["h10_per1_mes"]:$this->h10_per1_mes);
         $this->h10_per1_ano = ($this->h10_per1_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["h10_per1_ano"]:$this->h10_per1_ano);
         if($this->h10_per1_dia != ""){
            $this->h10_per1 = $this->h10_per1_ano."-".$this->h10_per1_mes."-".$this->h10_per1_dia;
         }
       }
       if($this->h10_per2 == ""){
         $this->h10_per2_dia = ($this->h10_per2_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["h10_per2_dia"]:$this->h10_per2_dia);
         $this->h10_per2_mes = ($this->h10_per2_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["h10_per2_mes"]:$this->h10_per2_mes);
         $this->h10_per2_ano = ($this->h10_per2_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["h10_per2_ano"]:$this->h10_per2_ano);
         if($this->h10_per2_dia != ""){
            $this->h10_per2 = $this->h10_per2_ano."-".$this->h10_per2_mes."-".$this->h10_per2_dia;
         }
       }
       $this->h10_art = ($this->h10_art == ""?@$GLOBALS["HTTP_POST_VARS"]["h10_art"]:$this->h10_art);
       $this->h10_lein = ($this->h10_lein == ""?@$GLOBALS["HTTP_POST_VARS"]["h10_lein"]:$this->h10_lein);
       $this->h10_locpu = ($this->h10_locpu == ""?@$GLOBALS["HTTP_POST_VARS"]["h10_locpu"]:$this->h10_locpu);
       $this->h10_perpu = ($this->h10_perpu == ""?@$GLOBALS["HTTP_POST_VARS"]["h10_perpu"]:$this->h10_perpu);
       $this->h10_portc = ($this->h10_portc == ""?@$GLOBALS["HTTP_POST_VARS"]["h10_portc"]:$this->h10_portc);
       $this->h10_ndias = ($this->h10_ndias == ""?@$GLOBALS["HTTP_POST_VARS"]["h10_ndias"]:$this->h10_ndias);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->h10_regist == null ){ 
       $this->erro_sql = " Campo Codigo do Funcionario nao Informado.";
       $this->erro_campo = "h10_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h10_emissa == null ){ 
       $this->erro_sql = " Campo Data da Emissao nao Informado.";
       $this->erro_campo = "h10_emissa_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h10_porta == null ){ 
       $this->erro_sql = " Campo Número da Portaria nao Informado.";
       $this->erro_campo = "h10_porta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h10_pref == null ){ 
       $this->erro_sql = " Campo Nome do Responsável nao Informado.";
       $this->erro_campo = "h10_pref";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h10_cargas == null ){ 
       $this->erro_sql = " Campo Quem Determinou nao Informado.";
       $this->erro_campo = "h10_cargas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h10_proc == null ){ 
       $this->erro_sql = " Campo Código do Processo nao Informado.";
       $this->erro_campo = "h10_proc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h10_cargo == null ){ 
       $this->erro_sql = " Campo Cargo do Servidor nao Informado.";
       $this->erro_campo = "h10_cargo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h10_nmes == null ){ 
       $this->erro_sql = " Campo mes da Emissao nao Informado.";
       $this->erro_campo = "h10_nmes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h10_per1 == null ){ 
       $this->erro_sql = " Campo Periodo Inicial nao Informado.";
       $this->erro_campo = "h10_per1_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h10_per2 == null ){ 
       $this->erro_sql = " Campo Periodo Final nao Informado.";
       $this->erro_campo = "h10_per2_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h10_art == null ){ 
       $this->erro_sql = " Campo Artigo da Lei nao Informado.";
       $this->erro_campo = "h10_art";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h10_lein == null ){ 
       $this->erro_sql = " Campo Lei Portaria nao Informado.";
       $this->erro_campo = "h10_lein";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h10_locpu == null ){ 
       $this->erro_sql = " Campo Local da Publicacao nao Informado.";
       $this->erro_campo = "h10_locpu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h10_perpu == null ){ 
       $this->erro_sql = " Campo Período Publicação nao Informado.";
       $this->erro_campo = "h10_perpu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h10_portc == null ){ 
       $this->erro_sql = " Campo Portaria de Consseção nao Informado.";
       $this->erro_campo = "h10_portc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h10_ndias == null ){ 
       $this->erro_sql = " Campo nr dias a convertidos nao Informado.";
       $this->erro_campo = "h10_ndias";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pcvlp(
                                       h10_regist 
                                      ,h10_emissa 
                                      ,h10_porta 
                                      ,h10_pref 
                                      ,h10_cargas 
                                      ,h10_proc 
                                      ,h10_cargo 
                                      ,h10_nmes 
                                      ,h10_per1 
                                      ,h10_per2 
                                      ,h10_art 
                                      ,h10_lein 
                                      ,h10_locpu 
                                      ,h10_perpu 
                                      ,h10_portc 
                                      ,h10_ndias 
                       )
                values (
                                $this->h10_regist 
                               ,".($this->h10_emissa == "null" || $this->h10_emissa == ""?"null":"'".$this->h10_emissa."'")." 
                               ,'$this->h10_porta' 
                               ,'$this->h10_pref' 
                               ,'$this->h10_cargas' 
                               ,'$this->h10_proc' 
                               ,'$this->h10_cargo' 
                               ,'$this->h10_nmes' 
                               ,".($this->h10_per1 == "null" || $this->h10_per1 == ""?"null":"'".$this->h10_per1."'")." 
                               ,".($this->h10_per2 == "null" || $this->h10_per2 == ""?"null":"'".$this->h10_per2."'")." 
                               ,'$this->h10_art' 
                               ,'$this->h10_lein' 
                               ,'$this->h10_locpu' 
                               ,'$this->h10_perpu' 
                               ,'$this->h10_portc' 
                               ,'$this->h10_ndias' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro das Portaria de Consecao                  () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro das Portaria de Consecao                  já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro das Portaria de Consecao                  () nao Incluído. Inclusao Abortada.";
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
     $sql = " update pcvlp set ";
     $virgula = "";
     if(trim($this->h10_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h10_regist"])){ 
       $sql  .= $virgula." h10_regist = $this->h10_regist ";
       $virgula = ",";
       if(trim($this->h10_regist) == null ){ 
         $this->erro_sql = " Campo Codigo do Funcionario nao Informado.";
         $this->erro_campo = "h10_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h10_emissa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h10_emissa_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["h10_emissa_dia"] !="") ){ 
       $sql  .= $virgula." h10_emissa = '$this->h10_emissa' ";
       $virgula = ",";
       if(trim($this->h10_emissa) == null ){ 
         $this->erro_sql = " Campo Data da Emissao nao Informado.";
         $this->erro_campo = "h10_emissa_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["h10_emissa_dia"])){ 
         $sql  .= $virgula." h10_emissa = null ";
         $virgula = ",";
         if(trim($this->h10_emissa) == null ){ 
           $this->erro_sql = " Campo Data da Emissao nao Informado.";
           $this->erro_campo = "h10_emissa_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->h10_porta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h10_porta"])){ 
       $sql  .= $virgula." h10_porta = '$this->h10_porta' ";
       $virgula = ",";
       if(trim($this->h10_porta) == null ){ 
         $this->erro_sql = " Campo Número da Portaria nao Informado.";
         $this->erro_campo = "h10_porta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h10_pref)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h10_pref"])){ 
       $sql  .= $virgula." h10_pref = '$this->h10_pref' ";
       $virgula = ",";
       if(trim($this->h10_pref) == null ){ 
         $this->erro_sql = " Campo Nome do Responsável nao Informado.";
         $this->erro_campo = "h10_pref";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h10_cargas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h10_cargas"])){ 
       $sql  .= $virgula." h10_cargas = '$this->h10_cargas' ";
       $virgula = ",";
       if(trim($this->h10_cargas) == null ){ 
         $this->erro_sql = " Campo Quem Determinou nao Informado.";
         $this->erro_campo = "h10_cargas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h10_proc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h10_proc"])){ 
       $sql  .= $virgula." h10_proc = '$this->h10_proc' ";
       $virgula = ",";
       if(trim($this->h10_proc) == null ){ 
         $this->erro_sql = " Campo Código do Processo nao Informado.";
         $this->erro_campo = "h10_proc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h10_cargo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h10_cargo"])){ 
       $sql  .= $virgula." h10_cargo = '$this->h10_cargo' ";
       $virgula = ",";
       if(trim($this->h10_cargo) == null ){ 
         $this->erro_sql = " Campo Cargo do Servidor nao Informado.";
         $this->erro_campo = "h10_cargo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h10_nmes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h10_nmes"])){ 
       $sql  .= $virgula." h10_nmes = '$this->h10_nmes' ";
       $virgula = ",";
       if(trim($this->h10_nmes) == null ){ 
         $this->erro_sql = " Campo mes da Emissao nao Informado.";
         $this->erro_campo = "h10_nmes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h10_per1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h10_per1_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["h10_per1_dia"] !="") ){ 
       $sql  .= $virgula." h10_per1 = '$this->h10_per1' ";
       $virgula = ",";
       if(trim($this->h10_per1) == null ){ 
         $this->erro_sql = " Campo Periodo Inicial nao Informado.";
         $this->erro_campo = "h10_per1_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["h10_per1_dia"])){ 
         $sql  .= $virgula." h10_per1 = null ";
         $virgula = ",";
         if(trim($this->h10_per1) == null ){ 
           $this->erro_sql = " Campo Periodo Inicial nao Informado.";
           $this->erro_campo = "h10_per1_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->h10_per2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h10_per2_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["h10_per2_dia"] !="") ){ 
       $sql  .= $virgula." h10_per2 = '$this->h10_per2' ";
       $virgula = ",";
       if(trim($this->h10_per2) == null ){ 
         $this->erro_sql = " Campo Periodo Final nao Informado.";
         $this->erro_campo = "h10_per2_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["h10_per2_dia"])){ 
         $sql  .= $virgula." h10_per2 = null ";
         $virgula = ",";
         if(trim($this->h10_per2) == null ){ 
           $this->erro_sql = " Campo Periodo Final nao Informado.";
           $this->erro_campo = "h10_per2_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->h10_art)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h10_art"])){ 
       $sql  .= $virgula." h10_art = '$this->h10_art' ";
       $virgula = ",";
       if(trim($this->h10_art) == null ){ 
         $this->erro_sql = " Campo Artigo da Lei nao Informado.";
         $this->erro_campo = "h10_art";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h10_lein)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h10_lein"])){ 
       $sql  .= $virgula." h10_lein = '$this->h10_lein' ";
       $virgula = ",";
       if(trim($this->h10_lein) == null ){ 
         $this->erro_sql = " Campo Lei Portaria nao Informado.";
         $this->erro_campo = "h10_lein";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h10_locpu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h10_locpu"])){ 
       $sql  .= $virgula." h10_locpu = '$this->h10_locpu' ";
       $virgula = ",";
       if(trim($this->h10_locpu) == null ){ 
         $this->erro_sql = " Campo Local da Publicacao nao Informado.";
         $this->erro_campo = "h10_locpu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h10_perpu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h10_perpu"])){ 
       $sql  .= $virgula." h10_perpu = '$this->h10_perpu' ";
       $virgula = ",";
       if(trim($this->h10_perpu) == null ){ 
         $this->erro_sql = " Campo Período Publicação nao Informado.";
         $this->erro_campo = "h10_perpu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h10_portc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h10_portc"])){ 
       $sql  .= $virgula." h10_portc = '$this->h10_portc' ";
       $virgula = ",";
       if(trim($this->h10_portc) == null ){ 
         $this->erro_sql = " Campo Portaria de Consseção nao Informado.";
         $this->erro_campo = "h10_portc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h10_ndias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h10_ndias"])){ 
       $sql  .= $virgula." h10_ndias = '$this->h10_ndias' ";
       $virgula = ",";
       if(trim($this->h10_ndias) == null ){ 
         $this->erro_sql = " Campo nr dias a convertidos nao Informado.";
         $this->erro_campo = "h10_ndias";
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
       $this->erro_sql   = "Cadastro das Portaria de Consecao                  nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro das Portaria de Consecao                  nao foi Alterado. Alteracao Executada.\\n";
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
     $sql = " delete from pcvlp
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
       $this->erro_sql   = "Cadastro das Portaria de Consecao                  nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro das Portaria de Consecao                  nao Encontrado. Exclusão não Efetuada.\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:pcvlp";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>