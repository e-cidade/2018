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
//CLASSE DA ENTIDADE folhaemp
class cl_folhaemp { 
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
   var $r42_anousu = 0; 
   var $r42_mesusu = 0; 
   var $r42_lotac = null; 
   var $r42_proati = null; 
   var $r42_rubric = null; 
   var $r42_elemen = null; 
   var $r42_proven = 0; 
   var $r42_descon = 0; 
   var $r42_arqui = null; 
   var $r42_tipo = null; 
   var $r42_reduz = 0; 
   var $r42_saldo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r42_anousu = int4 = Ano do Exercicio 
                 r42_mesusu = int4 = Mes do Exercicio 
                 r42_lotac = varchar(4) = Lotação 
                 r42_proati = varchar(4) = Projeto/Atividade 
                 r42_rubric = varchar(4) = Rubrica 
                 r42_elemen = varchar(12) = Elemento de despesa 
                 r42_proven = float8 = Valores dos Proventos 
                 r42_descon = float8 = Valores dos Descontos 
                 r42_arqui = varchar(3) = Sigla do Arquivo 
                 r42_tipo = varchar(1) = Tipo de Empenho 
                 r42_reduz = int4 = Codigo Reduzido da Dotacao 
                 r42_saldo = float8 = saldo da dotacao 
                 ";
   //funcao construtor da classe 
   function cl_folhaemp() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("folhaemp"); 
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
       $this->r42_anousu = ($this->r42_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r42_anousu"]:$this->r42_anousu);
       $this->r42_mesusu = ($this->r42_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r42_mesusu"]:$this->r42_mesusu);
       $this->r42_lotac = ($this->r42_lotac == ""?@$GLOBALS["HTTP_POST_VARS"]["r42_lotac"]:$this->r42_lotac);
       $this->r42_proati = ($this->r42_proati == ""?@$GLOBALS["HTTP_POST_VARS"]["r42_proati"]:$this->r42_proati);
       $this->r42_rubric = ($this->r42_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r42_rubric"]:$this->r42_rubric);
       $this->r42_elemen = ($this->r42_elemen == ""?@$GLOBALS["HTTP_POST_VARS"]["r42_elemen"]:$this->r42_elemen);
       $this->r42_proven = ($this->r42_proven == ""?@$GLOBALS["HTTP_POST_VARS"]["r42_proven"]:$this->r42_proven);
       $this->r42_descon = ($this->r42_descon == ""?@$GLOBALS["HTTP_POST_VARS"]["r42_descon"]:$this->r42_descon);
       $this->r42_arqui = ($this->r42_arqui == ""?@$GLOBALS["HTTP_POST_VARS"]["r42_arqui"]:$this->r42_arqui);
       $this->r42_tipo = ($this->r42_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["r42_tipo"]:$this->r42_tipo);
       $this->r42_reduz = ($this->r42_reduz == ""?@$GLOBALS["HTTP_POST_VARS"]["r42_reduz"]:$this->r42_reduz);
       $this->r42_saldo = ($this->r42_saldo == ""?@$GLOBALS["HTTP_POST_VARS"]["r42_saldo"]:$this->r42_saldo);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->r42_anousu == null ){ 
       $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
       $this->erro_campo = "r42_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r42_mesusu == null ){ 
       $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
       $this->erro_campo = "r42_mesusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r42_lotac == null ){ 
       $this->erro_sql = " Campo Lotação nao Informado.";
       $this->erro_campo = "r42_lotac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r42_proati == null ){ 
       $this->erro_sql = " Campo Projeto/Atividade nao Informado.";
       $this->erro_campo = "r42_proati";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r42_rubric == null ){ 
       $this->erro_sql = " Campo Rubrica nao Informado.";
       $this->erro_campo = "r42_rubric";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r42_elemen == null ){ 
       $this->erro_sql = " Campo Elemento de despesa nao Informado.";
       $this->erro_campo = "r42_elemen";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r42_proven == null ){ 
       $this->erro_sql = " Campo Valores dos Proventos nao Informado.";
       $this->erro_campo = "r42_proven";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r42_descon == null ){ 
       $this->erro_sql = " Campo Valores dos Descontos nao Informado.";
       $this->erro_campo = "r42_descon";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r42_arqui == null ){ 
       $this->erro_sql = " Campo Sigla do Arquivo nao Informado.";
       $this->erro_campo = "r42_arqui";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r42_tipo == null ){ 
       $this->erro_sql = " Campo Tipo de Empenho nao Informado.";
       $this->erro_campo = "r42_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r42_reduz == null ){ 
       $this->erro_sql = " Campo Codigo Reduzido da Dotacao nao Informado.";
       $this->erro_campo = "r42_reduz";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r42_saldo == null ){ 
       $this->erro_sql = " Campo saldo da dotacao nao Informado.";
       $this->erro_campo = "r42_saldo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into folhaemp(
                                       r42_anousu 
                                      ,r42_mesusu 
                                      ,r42_lotac 
                                      ,r42_proati 
                                      ,r42_rubric 
                                      ,r42_elemen 
                                      ,r42_proven 
                                      ,r42_descon 
                                      ,r42_arqui 
                                      ,r42_tipo 
                                      ,r42_reduz 
                                      ,r42_saldo 
                       )
                values (
                                $this->r42_anousu 
                               ,$this->r42_mesusu 
                               ,'$this->r42_lotac' 
                               ,'$this->r42_proati' 
                               ,'$this->r42_rubric' 
                               ,'$this->r42_elemen' 
                               ,$this->r42_proven 
                               ,$this->r42_descon 
                               ,'$this->r42_arqui' 
                               ,'$this->r42_tipo' 
                               ,$this->r42_reduz 
                               ,$this->r42_saldo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Empenhos da folha de pagamento                     () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Empenhos da folha de pagamento                     já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Empenhos da folha de pagamento                     () nao Incluído. Inclusao Abortada.";
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
     $sql = " update folhaemp set ";
     $virgula = "";
     if(trim($this->r42_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r42_anousu"])){ 
       $sql  .= $virgula." r42_anousu = $this->r42_anousu ";
       $virgula = ",";
       if(trim($this->r42_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "r42_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r42_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r42_mesusu"])){ 
       $sql  .= $virgula." r42_mesusu = $this->r42_mesusu ";
       $virgula = ",";
       if(trim($this->r42_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r42_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r42_lotac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r42_lotac"])){ 
       $sql  .= $virgula." r42_lotac = '$this->r42_lotac' ";
       $virgula = ",";
       if(trim($this->r42_lotac) == null ){ 
         $this->erro_sql = " Campo Lotação nao Informado.";
         $this->erro_campo = "r42_lotac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r42_proati)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r42_proati"])){ 
       $sql  .= $virgula." r42_proati = '$this->r42_proati' ";
       $virgula = ",";
       if(trim($this->r42_proati) == null ){ 
         $this->erro_sql = " Campo Projeto/Atividade nao Informado.";
         $this->erro_campo = "r42_proati";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r42_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r42_rubric"])){ 
       $sql  .= $virgula." r42_rubric = '$this->r42_rubric' ";
       $virgula = ",";
       if(trim($this->r42_rubric) == null ){ 
         $this->erro_sql = " Campo Rubrica nao Informado.";
         $this->erro_campo = "r42_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r42_elemen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r42_elemen"])){ 
       $sql  .= $virgula." r42_elemen = '$this->r42_elemen' ";
       $virgula = ",";
       if(trim($this->r42_elemen) == null ){ 
         $this->erro_sql = " Campo Elemento de despesa nao Informado.";
         $this->erro_campo = "r42_elemen";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r42_proven)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r42_proven"])){ 
       $sql  .= $virgula." r42_proven = $this->r42_proven ";
       $virgula = ",";
       if(trim($this->r42_proven) == null ){ 
         $this->erro_sql = " Campo Valores dos Proventos nao Informado.";
         $this->erro_campo = "r42_proven";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r42_descon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r42_descon"])){ 
       $sql  .= $virgula." r42_descon = $this->r42_descon ";
       $virgula = ",";
       if(trim($this->r42_descon) == null ){ 
         $this->erro_sql = " Campo Valores dos Descontos nao Informado.";
         $this->erro_campo = "r42_descon";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r42_arqui)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r42_arqui"])){ 
       $sql  .= $virgula." r42_arqui = '$this->r42_arqui' ";
       $virgula = ",";
       if(trim($this->r42_arqui) == null ){ 
         $this->erro_sql = " Campo Sigla do Arquivo nao Informado.";
         $this->erro_campo = "r42_arqui";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r42_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r42_tipo"])){ 
       $sql  .= $virgula." r42_tipo = '$this->r42_tipo' ";
       $virgula = ",";
       if(trim($this->r42_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo de Empenho nao Informado.";
         $this->erro_campo = "r42_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r42_reduz)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r42_reduz"])){ 
       $sql  .= $virgula." r42_reduz = $this->r42_reduz ";
       $virgula = ",";
       if(trim($this->r42_reduz) == null ){ 
         $this->erro_sql = " Campo Codigo Reduzido da Dotacao nao Informado.";
         $this->erro_campo = "r42_reduz";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r42_saldo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r42_saldo"])){ 
       $sql  .= $virgula." r42_saldo = $this->r42_saldo ";
       $virgula = ",";
       if(trim($this->r42_saldo) == null ){ 
         $this->erro_sql = " Campo saldo da dotacao nao Informado.";
         $this->erro_campo = "r42_saldo";
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
       $this->erro_sql   = "Empenhos da folha de pagamento                     nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Empenhos da folha de pagamento                     nao foi Alterado. Alteracao Executada.\\n";
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
     $sql = " delete from folhaemp
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
       $this->erro_sql   = "Empenhos da folha de pagamento                     nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Empenhos da folha de pagamento                     nao Encontrado. Exclusão não Efetuada.\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:folhaemp";
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
   function sql_query ( $oid = null,$campos="folhaemp.oid,*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from folhaemp ";
     $sql2 = "";
     if($dbwhere==""){
       if( $oid != "" && $oid != null){
          $sql2 = " where folhaemp.oid = '$oid'";
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
     $sql .= " from folhaemp ";
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