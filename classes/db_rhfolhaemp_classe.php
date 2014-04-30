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
//CLASSE DA ENTIDADE rhfolhaemp
class cl_rhfolhaemp { 
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
   var $rh42_anousu = 0; 
   var $rh42_mesusu = 0; 
   var $rh42_lotac = 0; 
   var $rh42_proati = 0; 
   var $rh42_rubric = null; 
   var $rh42_codele = 0; 
   var $rh42_proven = 0; 
   var $rh42_descon = 0; 
   var $rh42_arquiv = null; 
   var $rh42_tipo = 0; 
   var $rh42_reduz = 0; 
   var $rh42_saldo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh42_anousu = int4 = Ano 
                 rh42_mesusu = int4 = Mês 
                 rh42_lotac = int4 = Lotação 
                 rh42_proati = int4 = Projeto/Atividade 
                 rh42_rubric = char(4) = Rubrica 
                 rh42_codele = int4 = Elemento 
                 rh42_proven = float8 = Provento 
                 rh42_descon = float8 = Desconto 
                 rh42_arquiv = varchar(4) = Arquivo 
                 rh42_tipo = int4 = Item do Material 
                 rh42_reduz = int4 = Reduzido 
                 rh42_saldo = float8 = Saldo da Despesa 
                 ";
   //funcao construtor da classe 
   function cl_rhfolhaemp() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhfolhaemp"); 
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
       $this->rh42_anousu = ($this->rh42_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh42_anousu"]:$this->rh42_anousu);
       $this->rh42_mesusu = ($this->rh42_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh42_mesusu"]:$this->rh42_mesusu);
       $this->rh42_lotac = ($this->rh42_lotac == ""?@$GLOBALS["HTTP_POST_VARS"]["rh42_lotac"]:$this->rh42_lotac);
       $this->rh42_proati = ($this->rh42_proati == ""?@$GLOBALS["HTTP_POST_VARS"]["rh42_proati"]:$this->rh42_proati);
       $this->rh42_rubric = ($this->rh42_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["rh42_rubric"]:$this->rh42_rubric);
       $this->rh42_codele = ($this->rh42_codele == ""?@$GLOBALS["HTTP_POST_VARS"]["rh42_codele"]:$this->rh42_codele);
       $this->rh42_proven = ($this->rh42_proven == ""?@$GLOBALS["HTTP_POST_VARS"]["rh42_proven"]:$this->rh42_proven);
       $this->rh42_descon = ($this->rh42_descon == ""?@$GLOBALS["HTTP_POST_VARS"]["rh42_descon"]:$this->rh42_descon);
       $this->rh42_arquiv = ($this->rh42_arquiv == ""?@$GLOBALS["HTTP_POST_VARS"]["rh42_arquiv"]:$this->rh42_arquiv);
       $this->rh42_tipo = ($this->rh42_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh42_tipo"]:$this->rh42_tipo);
       $this->rh42_reduz = ($this->rh42_reduz == ""?@$GLOBALS["HTTP_POST_VARS"]["rh42_reduz"]:$this->rh42_reduz);
       $this->rh42_saldo = ($this->rh42_saldo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh42_saldo"]:$this->rh42_saldo);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->rh42_anousu == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "rh42_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh42_mesusu == null ){ 
       $this->erro_sql = " Campo Mês nao Informado.";
       $this->erro_campo = "rh42_mesusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh42_lotac == null ){ 
       $this->erro_sql = " Campo Lotação nao Informado.";
       $this->erro_campo = "rh42_lotac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh42_proati == null ){ 
       $this->erro_sql = " Campo Projeto/Atividade nao Informado.";
       $this->erro_campo = "rh42_proati";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh42_rubric == null ){ 
       $this->erro_sql = " Campo Rubrica nao Informado.";
       $this->erro_campo = "rh42_rubric";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh42_codele == null ){ 
       $this->erro_sql = " Campo Elemento nao Informado.";
       $this->erro_campo = "rh42_codele";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh42_proven == null ){ 
       $this->erro_sql = " Campo Provento nao Informado.";
       $this->erro_campo = "rh42_proven";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh42_descon == null ){ 
       $this->erro_sql = " Campo Desconto nao Informado.";
       $this->erro_campo = "rh42_descon";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh42_arquiv == null ){ 
       $this->erro_sql = " Campo Arquivo nao Informado.";
       $this->erro_campo = "rh42_arquiv";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh42_tipo == null ){ 
       $this->erro_sql = " Campo Item do Material nao Informado.";
       $this->erro_campo = "rh42_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh42_reduz == null ){ 
       $this->erro_sql = " Campo Reduzido nao Informado.";
       $this->erro_campo = "rh42_reduz";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh42_saldo == null ){ 
       $this->erro_sql = " Campo Saldo da Despesa nao Informado.";
       $this->erro_campo = "rh42_saldo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhfolhaemp(
                                       rh42_anousu 
                                      ,rh42_mesusu 
                                      ,rh42_lotac 
                                      ,rh42_proati 
                                      ,rh42_rubric 
                                      ,rh42_codele 
                                      ,rh42_proven 
                                      ,rh42_descon 
                                      ,rh42_arquiv 
                                      ,rh42_tipo 
                                      ,rh42_reduz 
                                      ,rh42_saldo 
                       )
                values (
                                $this->rh42_anousu 
                               ,$this->rh42_mesusu 
                               ,$this->rh42_lotac 
                               ,$this->rh42_proati 
                               ,'$this->rh42_rubric' 
                               ,$this->rh42_codele 
                               ,$this->rh42_proven 
                               ,$this->rh42_descon 
                               ,'$this->rh42_arquiv' 
                               ,$this->rh42_tipo 
                               ,$this->rh42_reduz 
                               ,$this->rh42_saldo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Arquivo para empenho () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Arquivo para empenho já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Arquivo para empenho () nao Incluído. Inclusao Abortada.";
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
     $sql = " update rhfolhaemp set ";
     $virgula = "";
     if(trim($this->rh42_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh42_anousu"])){ 
       $sql  .= $virgula." rh42_anousu = $this->rh42_anousu ";
       $virgula = ",";
       if(trim($this->rh42_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "rh42_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh42_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh42_mesusu"])){ 
       $sql  .= $virgula." rh42_mesusu = $this->rh42_mesusu ";
       $virgula = ",";
       if(trim($this->rh42_mesusu) == null ){ 
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "rh42_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh42_lotac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh42_lotac"])){ 
       $sql  .= $virgula." rh42_lotac = $this->rh42_lotac ";
       $virgula = ",";
       if(trim($this->rh42_lotac) == null ){ 
         $this->erro_sql = " Campo Lotação nao Informado.";
         $this->erro_campo = "rh42_lotac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh42_proati)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh42_proati"])){ 
       $sql  .= $virgula." rh42_proati = $this->rh42_proati ";
       $virgula = ",";
       if(trim($this->rh42_proati) == null ){ 
         $this->erro_sql = " Campo Projeto/Atividade nao Informado.";
         $this->erro_campo = "rh42_proati";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh42_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh42_rubric"])){ 
       $sql  .= $virgula." rh42_rubric = '$this->rh42_rubric' ";
       $virgula = ",";
       if(trim($this->rh42_rubric) == null ){ 
         $this->erro_sql = " Campo Rubrica nao Informado.";
         $this->erro_campo = "rh42_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh42_codele)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh42_codele"])){ 
       $sql  .= $virgula." rh42_codele = $this->rh42_codele ";
       $virgula = ",";
       if(trim($this->rh42_codele) == null ){ 
         $this->erro_sql = " Campo Elemento nao Informado.";
         $this->erro_campo = "rh42_codele";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh42_proven)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh42_proven"])){ 
       $sql  .= $virgula." rh42_proven = $this->rh42_proven ";
       $virgula = ",";
       if(trim($this->rh42_proven) == null ){ 
         $this->erro_sql = " Campo Provento nao Informado.";
         $this->erro_campo = "rh42_proven";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh42_descon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh42_descon"])){ 
       $sql  .= $virgula." rh42_descon = $this->rh42_descon ";
       $virgula = ",";
       if(trim($this->rh42_descon) == null ){ 
         $this->erro_sql = " Campo Desconto nao Informado.";
         $this->erro_campo = "rh42_descon";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh42_arquiv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh42_arquiv"])){ 
       $sql  .= $virgula." rh42_arquiv = '$this->rh42_arquiv' ";
       $virgula = ",";
       if(trim($this->rh42_arquiv) == null ){ 
         $this->erro_sql = " Campo Arquivo nao Informado.";
         $this->erro_campo = "rh42_arquiv";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh42_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh42_tipo"])){ 
       $sql  .= $virgula." rh42_tipo = $this->rh42_tipo ";
       $virgula = ",";
       if(trim($this->rh42_tipo) == null ){ 
         $this->erro_sql = " Campo Item do Material nao Informado.";
         $this->erro_campo = "rh42_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh42_reduz)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh42_reduz"])){ 
       $sql  .= $virgula." rh42_reduz = $this->rh42_reduz ";
       $virgula = ",";
       if(trim($this->rh42_reduz) == null ){ 
         $this->erro_sql = " Campo Reduzido nao Informado.";
         $this->erro_campo = "rh42_reduz";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh42_saldo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh42_saldo"])){ 
       $sql  .= $virgula." rh42_saldo = $this->rh42_saldo ";
       $virgula = ",";
       if(trim($this->rh42_saldo) == null ){ 
         $this->erro_sql = " Campo Saldo da Despesa nao Informado.";
         $this->erro_campo = "rh42_saldo";
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
       $this->erro_sql   = "Arquivo para empenho nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Arquivo para empenho nao foi Alterado. Alteracao Executada.\\n";
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
     $sql = " delete from rhfolhaemp
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
       $this->erro_sql   = "Arquivo para empenho nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Arquivo para empenho nao Encontrado. Exclusão não Efetuada.\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:rhfolhaemp";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $oid = null,$campos="rhfolhaemp.oid,*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhfolhaemp ";
     $sql .= "      inner join rubricas  on  rubricas.r06_anousu = rhfolhaemp.rh42_anousu and  rubricas.r06_mesusu = rhfolhaemp.rh42_mesusu and  rubricas.r06_codigo = rhfolhaemp.rh42_rubric";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = rhfolhaemp.rh42_codele and orcelemento.o56_anousu = ".db_getsession("DB_anousu");
     $sql .= "      inner join rhlota  on  rhlota.r70_codigo = rhfolhaemp.rh42_lotac";
     $sql .= "      inner join rhtipoemp  on  rhtipoemp.rh12_tipo = rhfolhaemp.rh42_tipo";
     $sql .= "      inner join db_estrutura  on  db_estrutura.db77_codestrut = rhlota.r70_codestrut";
     $sql2 = "";
     if($dbwhere==""){
       if( $oid != "" && $oid != null){
          $sql2 = " where rhfolhaemp.oid = '$oid'";
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
     $sql .= " from rhfolhaemp ";
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