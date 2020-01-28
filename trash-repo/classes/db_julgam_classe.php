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

//MODULO: licitação
//CLASSE DA ENTIDADE julgam
class cl_julgam { 
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
   var $l05_tipo = null; 
   var $l05_numero = null; 
   var $l05_numcgm = 0; 
   var $l05_item = null; 
   var $l05_valor = 0; 
   var $l05_condpg = null; 
   var $l05_prazo = 0; 
   var $l05_garant = 0; 
   var $l05_quant = 0; 
   var $l05_vlradj = 0; 
   var $l05_qtdadj = 0; 
   var $l05_dotac = 0; 
   var $l05_marca = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 l05_tipo = char(     1) = Tipo da Licitacao 
                 l05_numero = char(     8) = Numero da Licitacao 
                 l05_numcgm = int4 = Codigo do Fornecedor (CGM) 
                 l05_item = char(     7) = Codigo do Item (materiais) 
                 l05_valor = float8 = Valor Unitario * quantidade 
                 l05_condpg = char(    30) = Condicoes de pagamento 
                 l05_prazo = int4 = Prazo para entrega (em dias) 
                 l05_garant = int4 = Garantia (em dias) 
                 l05_quant = float8 = Quantidade 
                 l05_vlradj = float8 = Valor adjudicado 
                 l05_qtdadj = float8 = Quantidade adjudicada 
                 l05_dotac = int4 = Codigo Estrutural da Dotacao 
                 l05_marca = varchar(45) = Marca 
                 ";
   //funcao construtor da classe 
   function cl_julgam() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("julgam"); 
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
       $this->l05_tipo = ($this->l05_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["l05_tipo"]:$this->l05_tipo);
       $this->l05_numero = ($this->l05_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["l05_numero"]:$this->l05_numero);
       $this->l05_numcgm = ($this->l05_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["l05_numcgm"]:$this->l05_numcgm);
       $this->l05_item = ($this->l05_item == ""?@$GLOBALS["HTTP_POST_VARS"]["l05_item"]:$this->l05_item);
       $this->l05_valor = ($this->l05_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["l05_valor"]:$this->l05_valor);
       $this->l05_condpg = ($this->l05_condpg == ""?@$GLOBALS["HTTP_POST_VARS"]["l05_condpg"]:$this->l05_condpg);
       $this->l05_prazo = ($this->l05_prazo == ""?@$GLOBALS["HTTP_POST_VARS"]["l05_prazo"]:$this->l05_prazo);
       $this->l05_garant = ($this->l05_garant == ""?@$GLOBALS["HTTP_POST_VARS"]["l05_garant"]:$this->l05_garant);
       $this->l05_quant = ($this->l05_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["l05_quant"]:$this->l05_quant);
       $this->l05_vlradj = ($this->l05_vlradj == ""?@$GLOBALS["HTTP_POST_VARS"]["l05_vlradj"]:$this->l05_vlradj);
       $this->l05_qtdadj = ($this->l05_qtdadj == ""?@$GLOBALS["HTTP_POST_VARS"]["l05_qtdadj"]:$this->l05_qtdadj);
       $this->l05_dotac = ($this->l05_dotac == ""?@$GLOBALS["HTTP_POST_VARS"]["l05_dotac"]:$this->l05_dotac);
       $this->l05_marca = ($this->l05_marca == ""?@$GLOBALS["HTTP_POST_VARS"]["l05_marca"]:$this->l05_marca);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->l05_tipo == null ){ 
       $this->erro_sql = " Campo Tipo da Licitacao nao Informado.";
       $this->erro_campo = "l05_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l05_numero == null ){ 
       $this->erro_sql = " Campo Numero da Licitacao nao Informado.";
       $this->erro_campo = "l05_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l05_numcgm == null ){ 
       $this->erro_sql = " Campo Codigo do Fornecedor (CGM) nao Informado.";
       $this->erro_campo = "l05_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l05_item == null ){ 
       $this->erro_sql = " Campo Codigo do Item (materiais) nao Informado.";
       $this->erro_campo = "l05_item";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l05_valor == null ){ 
       $this->erro_sql = " Campo Valor Unitario * quantidade nao Informado.";
       $this->erro_campo = "l05_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l05_condpg == null ){ 
       $this->erro_sql = " Campo Condicoes de pagamento nao Informado.";
       $this->erro_campo = "l05_condpg";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l05_prazo == null ){ 
       $this->erro_sql = " Campo Prazo para entrega (em dias) nao Informado.";
       $this->erro_campo = "l05_prazo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l05_garant == null ){ 
       $this->erro_sql = " Campo Garantia (em dias) nao Informado.";
       $this->erro_campo = "l05_garant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l05_quant == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "l05_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l05_vlradj == null ){ 
       $this->erro_sql = " Campo Valor adjudicado nao Informado.";
       $this->erro_campo = "l05_vlradj";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l05_qtdadj == null ){ 
       $this->erro_sql = " Campo Quantidade adjudicada nao Informado.";
       $this->erro_campo = "l05_qtdadj";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l05_dotac == null ){ 
       $this->erro_sql = " Campo Codigo Estrutural da Dotacao nao Informado.";
       $this->erro_campo = "l05_dotac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into julgam(
                                       l05_tipo 
                                      ,l05_numero 
                                      ,l05_numcgm 
                                      ,l05_item 
                                      ,l05_valor 
                                      ,l05_condpg 
                                      ,l05_prazo 
                                      ,l05_garant 
                                      ,l05_quant 
                                      ,l05_vlradj 
                                      ,l05_qtdadj 
                                      ,l05_dotac 
                                      ,l05_marca 
                       )
                values (
                                '$this->l05_tipo' 
                               ,'$this->l05_numero' 
                               ,$this->l05_numcgm 
                               ,'$this->l05_item' 
                               ,$this->l05_valor 
                               ,'$this->l05_condpg' 
                               ,$this->l05_prazo 
                               ,$this->l05_garant 
                               ,$this->l05_quant 
                               ,$this->l05_vlradj 
                               ,$this->l05_qtdadj 
                               ,$this->l05_dotac 
                               ,'$this->l05_marca' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Contem dados dos itens cotados  por cada fornecedo () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Contem dados dos itens cotados  por cada fornecedo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Contem dados dos itens cotados  por cada fornecedo () nao Incluído. Inclusao Abortada.";
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
     $sql = " update julgam set ";
     $virgula = "";
     if(trim($this->l05_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l05_tipo"])){ 
       $sql  .= $virgula." l05_tipo = '$this->l05_tipo' ";
       $virgula = ",";
       if(trim($this->l05_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo da Licitacao nao Informado.";
         $this->erro_campo = "l05_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l05_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l05_numero"])){ 
       $sql  .= $virgula." l05_numero = '$this->l05_numero' ";
       $virgula = ",";
       if(trim($this->l05_numero) == null ){ 
         $this->erro_sql = " Campo Numero da Licitacao nao Informado.";
         $this->erro_campo = "l05_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l05_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l05_numcgm"])){ 
       $sql  .= $virgula." l05_numcgm = $this->l05_numcgm ";
       $virgula = ",";
       if(trim($this->l05_numcgm) == null ){ 
         $this->erro_sql = " Campo Codigo do Fornecedor (CGM) nao Informado.";
         $this->erro_campo = "l05_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l05_item)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l05_item"])){ 
       $sql  .= $virgula." l05_item = '$this->l05_item' ";
       $virgula = ",";
       if(trim($this->l05_item) == null ){ 
         $this->erro_sql = " Campo Codigo do Item (materiais) nao Informado.";
         $this->erro_campo = "l05_item";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l05_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l05_valor"])){ 
       $sql  .= $virgula." l05_valor = $this->l05_valor ";
       $virgula = ",";
       if(trim($this->l05_valor) == null ){ 
         $this->erro_sql = " Campo Valor Unitario * quantidade nao Informado.";
         $this->erro_campo = "l05_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l05_condpg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l05_condpg"])){ 
       $sql  .= $virgula." l05_condpg = '$this->l05_condpg' ";
       $virgula = ",";
       if(trim($this->l05_condpg) == null ){ 
         $this->erro_sql = " Campo Condicoes de pagamento nao Informado.";
         $this->erro_campo = "l05_condpg";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l05_prazo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l05_prazo"])){ 
       $sql  .= $virgula." l05_prazo = $this->l05_prazo ";
       $virgula = ",";
       if(trim($this->l05_prazo) == null ){ 
         $this->erro_sql = " Campo Prazo para entrega (em dias) nao Informado.";
         $this->erro_campo = "l05_prazo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l05_garant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l05_garant"])){ 
       $sql  .= $virgula." l05_garant = $this->l05_garant ";
       $virgula = ",";
       if(trim($this->l05_garant) == null ){ 
         $this->erro_sql = " Campo Garantia (em dias) nao Informado.";
         $this->erro_campo = "l05_garant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l05_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l05_quant"])){ 
       $sql  .= $virgula." l05_quant = $this->l05_quant ";
       $virgula = ",";
       if(trim($this->l05_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "l05_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l05_vlradj)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l05_vlradj"])){ 
       $sql  .= $virgula." l05_vlradj = $this->l05_vlradj ";
       $virgula = ",";
       if(trim($this->l05_vlradj) == null ){ 
         $this->erro_sql = " Campo Valor adjudicado nao Informado.";
         $this->erro_campo = "l05_vlradj";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l05_qtdadj)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l05_qtdadj"])){ 
       $sql  .= $virgula." l05_qtdadj = $this->l05_qtdadj ";
       $virgula = ",";
       if(trim($this->l05_qtdadj) == null ){ 
         $this->erro_sql = " Campo Quantidade adjudicada nao Informado.";
         $this->erro_campo = "l05_qtdadj";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l05_dotac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l05_dotac"])){ 
       $sql  .= $virgula." l05_dotac = $this->l05_dotac ";
       $virgula = ",";
       if(trim($this->l05_dotac) == null ){ 
         $this->erro_sql = " Campo Codigo Estrutural da Dotacao nao Informado.";
         $this->erro_campo = "l05_dotac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l05_marca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l05_marca"])){ 
       $sql  .= $virgula." l05_marca = '$this->l05_marca' ";
       $virgula = ",";
     }
     $sql .= " where ";
$sql .= "oid = '$oid'";     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Contem dados dos itens cotados  por cada fornecedo nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Contem dados dos itens cotados  por cada fornecedo nao foi Alterado. Alteracao Executada.\\n";
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
     $sql = " delete from julgam
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
       $this->erro_sql   = "Contem dados dos itens cotados  por cada fornecedo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Contem dados dos itens cotados  por cada fornecedo nao Encontrado. Exclusão não Efetuada.\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:julgam";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>