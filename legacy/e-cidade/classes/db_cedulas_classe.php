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
//CLASSE DA ENTIDADE cedulas
class cl_cedulas { 
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
   var $r05_anousu = 0; 
   var $r05_mesusu = 0; 
   var $r05_cedu0 = 0; 
   var $r05_cedu1 = 0; 
   var $r05_cedu2 = 0; 
   var $r05_cedu3 = 0; 
   var $r05_cedu4 = 0; 
   var $r05_cedu5 = 0; 
   var $r05_cedu6 = 0; 
   var $r05_cedu7 = 0; 
   var $r05_cedu8 = 0; 
   var $r05_cedu9 = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r05_anousu = int4 = Ano do Exercicio 
                 r05_mesusu = int4 = Mes do Exercicio 
                 r05_cedu0 = float8 = PRIMEIRA CEDULA DA TABELA 
                 r05_cedu1 = float8 = SEGUNDA CEDULA DA TABELA 
                 r05_cedu2 = float8 = TERCEIRA CEDULA DA TABELA 
                 r05_cedu3 = float8 = QUARTA CEDULA DA TABELA 
                 r05_cedu4 = float8 = QUINTA CEDULA DA TABELA 
                 r05_cedu5 = float8 = SEXTA CEDULA DA TABELA 
                 r05_cedu6 = float8 = SETIMA CEDULA DA TABELA 
                 r05_cedu7 = float8 = OITAVA CEDULA DA TABELA 
                 r05_cedu8 = float8 = NONA CEDULA DA TABELA 
                 r05_cedu9 = float8 = DECIMA CEDULA DA TABELA 
                 ";
   //funcao construtor da classe 
   function cl_cedulas() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cedulas"); 
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
       $this->r05_anousu = ($this->r05_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r05_anousu"]:$this->r05_anousu);
       $this->r05_mesusu = ($this->r05_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r05_mesusu"]:$this->r05_mesusu);
       $this->r05_cedu0 = ($this->r05_cedu0 == ""?@$GLOBALS["HTTP_POST_VARS"]["r05_cedu0"]:$this->r05_cedu0);
       $this->r05_cedu1 = ($this->r05_cedu1 == ""?@$GLOBALS["HTTP_POST_VARS"]["r05_cedu1"]:$this->r05_cedu1);
       $this->r05_cedu2 = ($this->r05_cedu2 == ""?@$GLOBALS["HTTP_POST_VARS"]["r05_cedu2"]:$this->r05_cedu2);
       $this->r05_cedu3 = ($this->r05_cedu3 == ""?@$GLOBALS["HTTP_POST_VARS"]["r05_cedu3"]:$this->r05_cedu3);
       $this->r05_cedu4 = ($this->r05_cedu4 == ""?@$GLOBALS["HTTP_POST_VARS"]["r05_cedu4"]:$this->r05_cedu4);
       $this->r05_cedu5 = ($this->r05_cedu5 == ""?@$GLOBALS["HTTP_POST_VARS"]["r05_cedu5"]:$this->r05_cedu5);
       $this->r05_cedu6 = ($this->r05_cedu6 == ""?@$GLOBALS["HTTP_POST_VARS"]["r05_cedu6"]:$this->r05_cedu6);
       $this->r05_cedu7 = ($this->r05_cedu7 == ""?@$GLOBALS["HTTP_POST_VARS"]["r05_cedu7"]:$this->r05_cedu7);
       $this->r05_cedu8 = ($this->r05_cedu8 == ""?@$GLOBALS["HTTP_POST_VARS"]["r05_cedu8"]:$this->r05_cedu8);
       $this->r05_cedu9 = ($this->r05_cedu9 == ""?@$GLOBALS["HTTP_POST_VARS"]["r05_cedu9"]:$this->r05_cedu9);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->r05_anousu == null ){ 
       $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
       $this->erro_campo = "r05_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r05_mesusu == null ){ 
       $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
       $this->erro_campo = "r05_mesusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r05_cedu0 == null ){ 
       $this->erro_sql = " Campo PRIMEIRA CEDULA DA TABELA nao Informado.";
       $this->erro_campo = "r05_cedu0";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r05_cedu1 == null ){ 
       $this->erro_sql = " Campo SEGUNDA CEDULA DA TABELA nao Informado.";
       $this->erro_campo = "r05_cedu1";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r05_cedu2 == null ){ 
       $this->erro_sql = " Campo TERCEIRA CEDULA DA TABELA nao Informado.";
       $this->erro_campo = "r05_cedu2";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r05_cedu3 == null ){ 
       $this->erro_sql = " Campo QUARTA CEDULA DA TABELA nao Informado.";
       $this->erro_campo = "r05_cedu3";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r05_cedu4 == null ){ 
       $this->erro_sql = " Campo QUINTA CEDULA DA TABELA nao Informado.";
       $this->erro_campo = "r05_cedu4";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r05_cedu5 == null ){ 
       $this->erro_sql = " Campo SEXTA CEDULA DA TABELA nao Informado.";
       $this->erro_campo = "r05_cedu5";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r05_cedu6 == null ){ 
       $this->erro_sql = " Campo SETIMA CEDULA DA TABELA nao Informado.";
       $this->erro_campo = "r05_cedu6";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r05_cedu7 == null ){ 
       $this->erro_sql = " Campo OITAVA CEDULA DA TABELA nao Informado.";
       $this->erro_campo = "r05_cedu7";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r05_cedu8 == null ){ 
       $this->erro_sql = " Campo NONA CEDULA DA TABELA nao Informado.";
       $this->erro_campo = "r05_cedu8";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r05_cedu9 == null ){ 
       $this->erro_sql = " Campo DECIMA CEDULA DA TABELA nao Informado.";
       $this->erro_campo = "r05_cedu9";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cedulas(
                                       r05_anousu 
                                      ,r05_mesusu 
                                      ,r05_cedu0 
                                      ,r05_cedu1 
                                      ,r05_cedu2 
                                      ,r05_cedu3 
                                      ,r05_cedu4 
                                      ,r05_cedu5 
                                      ,r05_cedu6 
                                      ,r05_cedu7 
                                      ,r05_cedu8 
                                      ,r05_cedu9 
                       )
                values (
                                $this->r05_anousu 
                               ,$this->r05_mesusu 
                               ,$this->r05_cedu0 
                               ,$this->r05_cedu1 
                               ,$this->r05_cedu2 
                               ,$this->r05_cedu3 
                               ,$this->r05_cedu4 
                               ,$this->r05_cedu5 
                               ,$this->r05_cedu6 
                               ,$this->r05_cedu7 
                               ,$this->r05_cedu8 
                               ,$this->r05_cedu9 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cadastro das Cedulas(dinheiro) para finsdo Relator () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cadastro das Cedulas(dinheiro) para finsdo Relator já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cadastro das Cedulas(dinheiro) para finsdo Relator () nao Incluído. Inclusao Abortada.";
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
     $sql = " update cedulas set ";
     $virgula = "";
     if(trim($this->r05_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r05_anousu"])){ 
       $sql  .= $virgula." r05_anousu = $this->r05_anousu ";
       $virgula = ",";
       if(trim($this->r05_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "r05_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r05_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r05_mesusu"])){ 
       $sql  .= $virgula." r05_mesusu = $this->r05_mesusu ";
       $virgula = ",";
       if(trim($this->r05_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r05_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r05_cedu0)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r05_cedu0"])){ 
       $sql  .= $virgula." r05_cedu0 = $this->r05_cedu0 ";
       $virgula = ",";
       if(trim($this->r05_cedu0) == null ){ 
         $this->erro_sql = " Campo PRIMEIRA CEDULA DA TABELA nao Informado.";
         $this->erro_campo = "r05_cedu0";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r05_cedu1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r05_cedu1"])){ 
       $sql  .= $virgula." r05_cedu1 = $this->r05_cedu1 ";
       $virgula = ",";
       if(trim($this->r05_cedu1) == null ){ 
         $this->erro_sql = " Campo SEGUNDA CEDULA DA TABELA nao Informado.";
         $this->erro_campo = "r05_cedu1";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r05_cedu2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r05_cedu2"])){ 
       $sql  .= $virgula." r05_cedu2 = $this->r05_cedu2 ";
       $virgula = ",";
       if(trim($this->r05_cedu2) == null ){ 
         $this->erro_sql = " Campo TERCEIRA CEDULA DA TABELA nao Informado.";
         $this->erro_campo = "r05_cedu2";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r05_cedu3)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r05_cedu3"])){ 
       $sql  .= $virgula." r05_cedu3 = $this->r05_cedu3 ";
       $virgula = ",";
       if(trim($this->r05_cedu3) == null ){ 
         $this->erro_sql = " Campo QUARTA CEDULA DA TABELA nao Informado.";
         $this->erro_campo = "r05_cedu3";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r05_cedu4)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r05_cedu4"])){ 
       $sql  .= $virgula." r05_cedu4 = $this->r05_cedu4 ";
       $virgula = ",";
       if(trim($this->r05_cedu4) == null ){ 
         $this->erro_sql = " Campo QUINTA CEDULA DA TABELA nao Informado.";
         $this->erro_campo = "r05_cedu4";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r05_cedu5)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r05_cedu5"])){ 
       $sql  .= $virgula." r05_cedu5 = $this->r05_cedu5 ";
       $virgula = ",";
       if(trim($this->r05_cedu5) == null ){ 
         $this->erro_sql = " Campo SEXTA CEDULA DA TABELA nao Informado.";
         $this->erro_campo = "r05_cedu5";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r05_cedu6)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r05_cedu6"])){ 
       $sql  .= $virgula." r05_cedu6 = $this->r05_cedu6 ";
       $virgula = ",";
       if(trim($this->r05_cedu6) == null ){ 
         $this->erro_sql = " Campo SETIMA CEDULA DA TABELA nao Informado.";
         $this->erro_campo = "r05_cedu6";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r05_cedu7)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r05_cedu7"])){ 
       $sql  .= $virgula." r05_cedu7 = $this->r05_cedu7 ";
       $virgula = ",";
       if(trim($this->r05_cedu7) == null ){ 
         $this->erro_sql = " Campo OITAVA CEDULA DA TABELA nao Informado.";
         $this->erro_campo = "r05_cedu7";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r05_cedu8)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r05_cedu8"])){ 
       $sql  .= $virgula." r05_cedu8 = $this->r05_cedu8 ";
       $virgula = ",";
       if(trim($this->r05_cedu8) == null ){ 
         $this->erro_sql = " Campo NONA CEDULA DA TABELA nao Informado.";
         $this->erro_campo = "r05_cedu8";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r05_cedu9)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r05_cedu9"])){ 
       $sql  .= $virgula." r05_cedu9 = $this->r05_cedu9 ";
       $virgula = ",";
       if(trim($this->r05_cedu9) == null ){ 
         $this->erro_sql = " Campo DECIMA CEDULA DA TABELA nao Informado.";
         $this->erro_campo = "r05_cedu9";
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
       $this->erro_sql   = "cadastro das Cedulas(dinheiro) para finsdo Relator nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cadastro das Cedulas(dinheiro) para finsdo Relator nao foi Alterado. Alteracao Executada.\\n";
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
     $sql = " delete from cedulas
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
       $this->erro_sql   = "cadastro das Cedulas(dinheiro) para finsdo Relator nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cadastro das Cedulas(dinheiro) para finsdo Relator nao Encontrado. Exclusão não Efetuada.\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:cedulas";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function atualiza_incluir (){
  	 $this->incluir();
   }
   function sql_query ( $oid = null,$campos="cedulas.oid,*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cedulas ";
     $sql2 = "";
     if($dbwhere==""){
       if( $oid != "" && $oid != null){
          $sql2 = " where cedulas.oid = '$oid'";
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
   function sql_query_file ( $oid = null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cedulas ";
     $sql2 = "";
     if($dbwhere==""){
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