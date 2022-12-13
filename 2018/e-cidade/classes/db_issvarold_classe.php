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

//MODULO: issqn
//CLASSE DA ENTIDADE issvarold
class cl_issvarold { 
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
   var $q22_codlev = 0; 
   var $q22_codigo = 0; 
   var $q22_numpre = 0; 
   var $q22_numpar = 0; 
   var $q22_valor = 0; 
   var $q22_ano = 0; 
   var $q22_mes = 0; 
   var $q22_histor = null; 
   var $q22_aliq = 0; 
   var $q22_bruto = 0; 
   var $q22_vlrinf = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q22_codlev = int4 = Levantamento 
                 q22_codigo = int8 = Código 
                 q22_numpre = int4 = Numpre 
                 q22_numpar = int4 = Parcela 
                 q22_valor = float8 = Valor 
                 q22_ano = int4 = Ano 
                 q22_mes = int4 = Mes 
                 q22_histor = text = Histórico 
                 q22_aliq = float8 = Aliquota 
                 q22_bruto = float4 = Bruto 
                 q22_vlrinf = float8 = valor contribuinte 
                 ";
   //funcao construtor da classe 
   function cl_issvarold() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("issvarold"); 
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
       $this->q22_codlev = ($this->q22_codlev == ""?@$GLOBALS["HTTP_POST_VARS"]["q22_codlev"]:$this->q22_codlev);
       $this->q22_codigo = ($this->q22_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["q22_codigo"]:$this->q22_codigo);
       $this->q22_numpre = ($this->q22_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["q22_numpre"]:$this->q22_numpre);
       $this->q22_numpar = ($this->q22_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["q22_numpar"]:$this->q22_numpar);
       $this->q22_valor = ($this->q22_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["q22_valor"]:$this->q22_valor);
       $this->q22_ano = ($this->q22_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q22_ano"]:$this->q22_ano);
       $this->q22_mes = ($this->q22_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q22_mes"]:$this->q22_mes);
       $this->q22_histor = ($this->q22_histor == ""?@$GLOBALS["HTTP_POST_VARS"]["q22_histor"]:$this->q22_histor);
       $this->q22_aliq = ($this->q22_aliq == ""?@$GLOBALS["HTTP_POST_VARS"]["q22_aliq"]:$this->q22_aliq);
       $this->q22_bruto = ($this->q22_bruto == ""?@$GLOBALS["HTTP_POST_VARS"]["q22_bruto"]:$this->q22_bruto);
       $this->q22_vlrinf = ($this->q22_vlrinf == ""?@$GLOBALS["HTTP_POST_VARS"]["q22_vlrinf"]:$this->q22_vlrinf);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->q22_codlev == null ){ 
       $this->erro_sql = " Campo Levantamento nao Informado.";
       $this->erro_campo = "q22_codlev";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q22_codigo == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "q22_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q22_numpre == null ){ 
       $this->erro_sql = " Campo Numpre nao Informado.";
       $this->erro_campo = "q22_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q22_numpar == null ){ 
       $this->erro_sql = " Campo Parcela nao Informado.";
       $this->erro_campo = "q22_numpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q22_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "q22_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q22_ano == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "q22_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q22_mes == null ){ 
       $this->erro_sql = " Campo Mes nao Informado.";
       $this->erro_campo = "q22_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q22_histor == null ){ 
       $this->erro_sql = " Campo Histórico nao Informado.";
       $this->erro_campo = "q22_histor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q22_aliq == null ){ 
       $this->erro_sql = " Campo Aliquota nao Informado.";
       $this->erro_campo = "q22_aliq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q22_bruto == null ){ 
       $this->erro_sql = " Campo Bruto nao Informado.";
       $this->erro_campo = "q22_bruto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q22_vlrinf == null ){ 
       $this->erro_sql = " Campo valor contribuinte nao Informado.";
       $this->erro_campo = "q22_vlrinf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into issvarold(
                                       q22_codlev 
                                      ,q22_codigo 
                                      ,q22_numpre 
                                      ,q22_numpar 
                                      ,q22_valor 
                                      ,q22_ano 
                                      ,q22_mes 
                                      ,q22_histor 
                                      ,q22_aliq 
                                      ,q22_bruto 
                                      ,q22_vlrinf 
                       )
                values (
                                $this->q22_codlev 
                               ,$this->q22_codigo 
                               ,$this->q22_numpre 
                               ,$this->q22_numpar 
                               ,$this->q22_valor 
                               ,$this->q22_ano 
                               ,$this->q22_mes 
                               ,'$this->q22_histor' 
                               ,$this->q22_aliq 
                               ,$this->q22_bruto 
                               ,$this->q22_vlrinf 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Backup do issvar () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Backup do issvar já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Backup do issvar () nao Incluído. Inclusao Abortada.";
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
     $sql = " update issvarold set ";
     $virgula = "";
     if(trim($this->q22_codlev)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q22_codlev"])){ 
       $sql  .= $virgula." q22_codlev = $this->q22_codlev ";
       $virgula = ",";
       if(trim($this->q22_codlev) == null ){ 
         $this->erro_sql = " Campo Levantamento nao Informado.";
         $this->erro_campo = "q22_codlev";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q22_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q22_codigo"])){ 
       $sql  .= $virgula." q22_codigo = $this->q22_codigo ";
       $virgula = ",";
       if(trim($this->q22_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "q22_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q22_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q22_numpre"])){ 
       $sql  .= $virgula." q22_numpre = $this->q22_numpre ";
       $virgula = ",";
       if(trim($this->q22_numpre) == null ){ 
         $this->erro_sql = " Campo Numpre nao Informado.";
         $this->erro_campo = "q22_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q22_numpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q22_numpar"])){ 
       $sql  .= $virgula." q22_numpar = $this->q22_numpar ";
       $virgula = ",";
       if(trim($this->q22_numpar) == null ){ 
         $this->erro_sql = " Campo Parcela nao Informado.";
         $this->erro_campo = "q22_numpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q22_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q22_valor"])){ 
       $sql  .= $virgula." q22_valor = $this->q22_valor ";
       $virgula = ",";
       if(trim($this->q22_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "q22_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q22_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q22_ano"])){ 
       $sql  .= $virgula." q22_ano = $this->q22_ano ";
       $virgula = ",";
       if(trim($this->q22_ano) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "q22_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q22_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q22_mes"])){ 
       $sql  .= $virgula." q22_mes = $this->q22_mes ";
       $virgula = ",";
       if(trim($this->q22_mes) == null ){ 
         $this->erro_sql = " Campo Mes nao Informado.";
         $this->erro_campo = "q22_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q22_histor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q22_histor"])){ 
       $sql  .= $virgula." q22_histor = '$this->q22_histor' ";
       $virgula = ",";
       if(trim($this->q22_histor) == null ){ 
         $this->erro_sql = " Campo Histórico nao Informado.";
         $this->erro_campo = "q22_histor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q22_aliq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q22_aliq"])){ 
       $sql  .= $virgula." q22_aliq = $this->q22_aliq ";
       $virgula = ",";
       if(trim($this->q22_aliq) == null ){ 
         $this->erro_sql = " Campo Aliquota nao Informado.";
         $this->erro_campo = "q22_aliq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q22_bruto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q22_bruto"])){ 
       $sql  .= $virgula." q22_bruto = $this->q22_bruto ";
       $virgula = ",";
       if(trim($this->q22_bruto) == null ){ 
         $this->erro_sql = " Campo Bruto nao Informado.";
         $this->erro_campo = "q22_bruto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q22_vlrinf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q22_vlrinf"])){ 
       $sql  .= $virgula." q22_vlrinf = $this->q22_vlrinf ";
       $virgula = ",";
       if(trim($this->q22_vlrinf) == null ){ 
         $this->erro_sql = " Campo valor contribuinte nao Informado.";
         $this->erro_campo = "q22_vlrinf";
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
       $this->erro_sql   = "Backup do issvar nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Backup do issvar nao foi Alterado. Alteracao Executada.\\n";
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
     $sql = " delete from issvarold
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
       $this->erro_sql   = "Backup do issvar nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Backup do issvar nao Encontrado. Exclusão não Efetuada.\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:issvarold";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $oid = null,$campos="issvarold.oid,*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issvarold ";
     $sql2 = "";
     if($dbwhere==""){
       if( $oid != "" && $oid != null){
          $sql2 = " where issvarold.oid = '$oid'";
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
     $sql .= " from issvarold ";
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