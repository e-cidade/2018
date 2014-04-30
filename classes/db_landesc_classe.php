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
//CLASSE DA ENTIDADE landesc
class cl_landesc { 
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
   var $r28_anousu = 0; 
   var $r28_mesusu = 0; 
   var $r28_regist = 0; 
   var $r28_codigo = null; 
   var $r28_quant = 0; 
   var $r28_valor = 0; 
   var $r28_seq = 0; 
   var $r28_lotac = null; 
   var $r28_ordcom = null; 
   var $r28_dtemis_dia = null; 
   var $r28_dtemis_mes = null; 
   var $r28_dtemis_ano = null; 
   var $r28_dtemis = null; 
   var $r28_login = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r28_anousu = int4 = Ano do Exercicio 
                 r28_mesusu = int4 = Mes do Exercicio 
                 r28_regist = int4 = Codigo do Funcionario 
                 r28_codigo = varchar(4) = Código do Desconto 
                 r28_quant = int4 = Quantidade Lanc no Desconto 
                 r28_valor = float8 = Valor do Desconto 
                 r28_seq = int4 = Numera os Registros 
                 r28_lotac = varchar(4) = Lotação do Servidor 
                 r28_ordcom = varchar(5) = Nro Ordem de Compra 
                 r28_dtemis = date = Data de Emissao da O. Compra 
                 r28_login = varchar(8) = Login 
                 ";
   //funcao construtor da classe 
   function cl_landesc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("landesc"); 
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
       $this->r28_anousu = ($this->r28_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r28_anousu"]:$this->r28_anousu);
       $this->r28_mesusu = ($this->r28_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r28_mesusu"]:$this->r28_mesusu);
       $this->r28_regist = ($this->r28_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r28_regist"]:$this->r28_regist);
       $this->r28_codigo = ($this->r28_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["r28_codigo"]:$this->r28_codigo);
       $this->r28_quant = ($this->r28_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["r28_quant"]:$this->r28_quant);
       $this->r28_valor = ($this->r28_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["r28_valor"]:$this->r28_valor);
       $this->r28_seq = ($this->r28_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["r28_seq"]:$this->r28_seq);
       $this->r28_lotac = ($this->r28_lotac == ""?@$GLOBALS["HTTP_POST_VARS"]["r28_lotac"]:$this->r28_lotac);
       $this->r28_ordcom = ($this->r28_ordcom == ""?@$GLOBALS["HTTP_POST_VARS"]["r28_ordcom"]:$this->r28_ordcom);
       if($this->r28_dtemis == ""){
         $this->r28_dtemis_dia = ($this->r28_dtemis_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r28_dtemis_dia"]:$this->r28_dtemis_dia);
         $this->r28_dtemis_mes = ($this->r28_dtemis_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r28_dtemis_mes"]:$this->r28_dtemis_mes);
         $this->r28_dtemis_ano = ($this->r28_dtemis_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r28_dtemis_ano"]:$this->r28_dtemis_ano);
         if($this->r28_dtemis_dia != ""){
            $this->r28_dtemis = $this->r28_dtemis_ano."-".$this->r28_dtemis_mes."-".$this->r28_dtemis_dia;
         }
       }
       $this->r28_login = ($this->r28_login == ""?@$GLOBALS["HTTP_POST_VARS"]["r28_login"]:$this->r28_login);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->r28_anousu == null ){ 
       $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
       $this->erro_campo = "r28_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r28_mesusu == null ){ 
       $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
       $this->erro_campo = "r28_mesusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r28_regist == null ){ 
       $this->erro_sql = " Campo Codigo do Funcionario nao Informado.";
       $this->erro_campo = "r28_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r28_codigo == null ){ 
       $this->erro_sql = " Campo Código do Desconto nao Informado.";
       $this->erro_campo = "r28_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r28_quant == null ){ 
       $this->erro_sql = " Campo Quantidade Lanc no Desconto nao Informado.";
       $this->erro_campo = "r28_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r28_valor == null ){ 
       $this->erro_sql = " Campo Valor do Desconto nao Informado.";
       $this->erro_campo = "r28_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r28_seq == null ){ 
       $this->erro_sql = " Campo Numera os Registros nao Informado.";
       $this->erro_campo = "r28_seq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r28_lotac == null ){ 
       $this->erro_sql = " Campo Lotação do Servidor nao Informado.";
       $this->erro_campo = "r28_lotac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r28_ordcom == null ){ 
       $this->erro_sql = " Campo Nro Ordem de Compra nao Informado.";
       $this->erro_campo = "r28_ordcom";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r28_dtemis == null ){ 
       $this->erro_sql = " Campo Data de Emissao da O. Compra nao Informado.";
       $this->erro_campo = "r28_dtemis_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r28_login == null ){ 
       $this->erro_sql = " Campo Login nao Informado.";
       $this->erro_campo = "r28_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into landesc(
                                       r28_anousu 
                                      ,r28_mesusu 
                                      ,r28_regist 
                                      ,r28_codigo 
                                      ,r28_quant 
                                      ,r28_valor 
                                      ,r28_seq 
                                      ,r28_lotac 
                                      ,r28_ordcom 
                                      ,r28_dtemis 
                                      ,r28_login 
                       )
                values (
                                $this->r28_anousu 
                               ,$this->r28_mesusu 
                               ,$this->r28_regist 
                               ,'$this->r28_codigo' 
                               ,$this->r28_quant 
                               ,$this->r28_valor 
                               ,$this->r28_seq 
                               ,'$this->r28_lotac' 
                               ,'$this->r28_ordcom' 
                               ,".($this->r28_dtemis == "null" || $this->r28_dtemis == ""?"null":"'".$this->r28_dtemis."'")." 
                               ,'$this->r28_login' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro dos descontos dos Funcionarios () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro dos descontos dos Funcionarios já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro dos descontos dos Funcionarios () nao Incluído. Inclusao Abortada.";
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
     $sql = " update landesc set ";
     $virgula = "";
     if(trim($this->r28_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r28_anousu"])){ 
       $sql  .= $virgula." r28_anousu = $this->r28_anousu ";
       $virgula = ",";
       if(trim($this->r28_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "r28_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r28_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r28_mesusu"])){ 
       $sql  .= $virgula." r28_mesusu = $this->r28_mesusu ";
       $virgula = ",";
       if(trim($this->r28_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r28_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r28_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r28_regist"])){ 
       $sql  .= $virgula." r28_regist = $this->r28_regist ";
       $virgula = ",";
       if(trim($this->r28_regist) == null ){ 
         $this->erro_sql = " Campo Codigo do Funcionario nao Informado.";
         $this->erro_campo = "r28_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r28_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r28_codigo"])){ 
       $sql  .= $virgula." r28_codigo = '$this->r28_codigo' ";
       $virgula = ",";
       if(trim($this->r28_codigo) == null ){ 
         $this->erro_sql = " Campo Código do Desconto nao Informado.";
         $this->erro_campo = "r28_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r28_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r28_quant"])){ 
       $sql  .= $virgula." r28_quant = $this->r28_quant ";
       $virgula = ",";
       if(trim($this->r28_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade Lanc no Desconto nao Informado.";
         $this->erro_campo = "r28_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r28_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r28_valor"])){ 
       $sql  .= $virgula." r28_valor = $this->r28_valor ";
       $virgula = ",";
       if(trim($this->r28_valor) == null ){ 
         $this->erro_sql = " Campo Valor do Desconto nao Informado.";
         $this->erro_campo = "r28_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r28_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r28_seq"])){ 
       $sql  .= $virgula." r28_seq = $this->r28_seq ";
       $virgula = ",";
       if(trim($this->r28_seq) == null ){ 
         $this->erro_sql = " Campo Numera os Registros nao Informado.";
         $this->erro_campo = "r28_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r28_lotac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r28_lotac"])){ 
       $sql  .= $virgula." r28_lotac = '$this->r28_lotac' ";
       $virgula = ",";
       if(trim($this->r28_lotac) == null ){ 
         $this->erro_sql = " Campo Lotação do Servidor nao Informado.";
         $this->erro_campo = "r28_lotac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r28_ordcom)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r28_ordcom"])){ 
       $sql  .= $virgula." r28_ordcom = '$this->r28_ordcom' ";
       $virgula = ",";
       if(trim($this->r28_ordcom) == null ){ 
         $this->erro_sql = " Campo Nro Ordem de Compra nao Informado.";
         $this->erro_campo = "r28_ordcom";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r28_dtemis)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r28_dtemis_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r28_dtemis_dia"] !="") ){ 
       $sql  .= $virgula." r28_dtemis = '$this->r28_dtemis' ";
       $virgula = ",";
       if(trim($this->r28_dtemis) == null ){ 
         $this->erro_sql = " Campo Data de Emissao da O. Compra nao Informado.";
         $this->erro_campo = "r28_dtemis_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r28_dtemis_dia"])){ 
         $sql  .= $virgula." r28_dtemis = null ";
         $virgula = ",";
         if(trim($this->r28_dtemis) == null ){ 
           $this->erro_sql = " Campo Data de Emissao da O. Compra nao Informado.";
           $this->erro_campo = "r28_dtemis_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->r28_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r28_login"])){ 
       $sql  .= $virgula." r28_login = '$this->r28_login' ";
       $virgula = ",";
       if(trim($this->r28_login) == null ){ 
         $this->erro_sql = " Campo Login nao Informado.";
         $this->erro_campo = "r28_login";
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
       $this->erro_sql   = "Cadastro dos descontos dos Funcionarios nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro dos descontos dos Funcionarios nao foi Alterado. Alteracao Executada.\\n";
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
     $sql = " delete from landesc
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
       $this->erro_sql   = "Cadastro dos descontos dos Funcionarios nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro dos descontos dos Funcionarios nao Encontrado. Exclusão não Efetuada.\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:landesc";
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
   function sql_query ( $oid = null,$campos="landesc.oid,*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from landesc ";
     $sql2 = "";
     if($dbwhere==""){
       if( $oid != "" && $oid != null){
          $sql2 = " where landesc.oid = '$oid'";
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
     $sql .= " from landesc ";
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