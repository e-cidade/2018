<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcprojativ
class cl_orcprojativ { 
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
   var $o55_anousu = 0; 
   var $o55_tipo = 0; 
   var $o55_projativ = 0; 
   var $o55_descr = null; 
   var $o55_finali = null; 
   var $o55_instit = 0; 
   var $o55_descrunidade = null; 
   var $o55_valorunidade = 0; 
   var $o55_especproduto = null; 
   var $o55_tipoacao = 0; 
   var $o55_formaimplementacao = 0; 
   var $o55_detalhamentoimp = null; 
   var $o55_origemacao = null; 
   var $o55_baselegal = null; 
   var $o55_orcproduto = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o55_anousu = int4 = Exercício 
                 o55_tipo = int4 = Tipo 
                 o55_projativ = int4 = Projetos / Atividades 
                 o55_descr = varchar(40) = Descrição 
                 o55_finali = text = Finalidade 
                 o55_instit = int4 = Código da instituicao 
                 o55_descrunidade = text = Unidade de Medida 
                 o55_valorunidade = float4 = Unidade de Medida ( Valor ) 
                 o55_especproduto = text = Especificação do Produto 
                 o55_tipoacao = int4 = Tipo de Ação 
                 o55_formaimplementacao = int4 = Forma de Implementação 
                 o55_detalhamentoimp = text = Detalhamento da Implementação 
                 o55_origemacao = text = Origem da Ação 
                 o55_baselegal = text = Base Legal 
                 o55_orcproduto = int4 = Produto 
                 ";
   //funcao construtor da classe 
   function cl_orcprojativ() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcprojativ"); 
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
       $this->o55_anousu = ($this->o55_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o55_anousu"]:$this->o55_anousu);
       $this->o55_tipo = ($this->o55_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["o55_tipo"]:$this->o55_tipo);
       $this->o55_projativ = ($this->o55_projativ == ""?@$GLOBALS["HTTP_POST_VARS"]["o55_projativ"]:$this->o55_projativ);
       $this->o55_descr = ($this->o55_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["o55_descr"]:$this->o55_descr);
       $this->o55_finali = ($this->o55_finali == ""?@$GLOBALS["HTTP_POST_VARS"]["o55_finali"]:$this->o55_finali);
       $this->o55_instit = ($this->o55_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["o55_instit"]:$this->o55_instit);
       $this->o55_descrunidade = ($this->o55_descrunidade == ""?@$GLOBALS["HTTP_POST_VARS"]["o55_descrunidade"]:$this->o55_descrunidade);
       $this->o55_valorunidade = ($this->o55_valorunidade == ""?@$GLOBALS["HTTP_POST_VARS"]["o55_valorunidade"]:$this->o55_valorunidade);
       $this->o55_especproduto = ($this->o55_especproduto == ""?@$GLOBALS["HTTP_POST_VARS"]["o55_especproduto"]:$this->o55_especproduto);
       $this->o55_tipoacao = ($this->o55_tipoacao == ""?@$GLOBALS["HTTP_POST_VARS"]["o55_tipoacao"]:$this->o55_tipoacao);
       $this->o55_formaimplementacao = ($this->o55_formaimplementacao == ""?@$GLOBALS["HTTP_POST_VARS"]["o55_formaimplementacao"]:$this->o55_formaimplementacao);
       $this->o55_detalhamentoimp = ($this->o55_detalhamentoimp == ""?@$GLOBALS["HTTP_POST_VARS"]["o55_detalhamentoimp"]:$this->o55_detalhamentoimp);
       $this->o55_origemacao = ($this->o55_origemacao == ""?@$GLOBALS["HTTP_POST_VARS"]["o55_origemacao"]:$this->o55_origemacao);
       $this->o55_baselegal = ($this->o55_baselegal == ""?@$GLOBALS["HTTP_POST_VARS"]["o55_baselegal"]:$this->o55_baselegal);
       $this->o55_orcproduto = ($this->o55_orcproduto == ""?@$GLOBALS["HTTP_POST_VARS"]["o55_orcproduto"]:$this->o55_orcproduto);
     }else{
       $this->o55_anousu = ($this->o55_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o55_anousu"]:$this->o55_anousu);
       $this->o55_projativ = ($this->o55_projativ == ""?@$GLOBALS["HTTP_POST_VARS"]["o55_projativ"]:$this->o55_projativ);
     }
   }
   // funcao para inclusao
   function incluir ($o55_anousu,$o55_projativ){ 
      $this->atualizacampos();
     if($this->o55_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "o55_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o55_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "o55_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o55_instit == null ){ 
       $this->erro_sql = " Campo Código da instituicao nao Informado.";
       $this->erro_campo = "o55_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o55_valorunidade == null ){ 
       $this->o55_valorunidade = "0";
     }
     if($this->o55_tipoacao == null ){ 
       $this->erro_sql = " Campo Tipo de Ação nao Informado.";
       $this->erro_campo = "o55_tipoacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o55_formaimplementacao == null ){ 
       $this->erro_sql = " Campo Forma de Implementação nao Informado.";
       $this->erro_campo = "o55_formaimplementacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o55_orcproduto == null ){ 
       $this->erro_sql = " Campo Produto nao Informado.";
       $this->erro_campo = "o55_orcproduto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->o55_anousu = $o55_anousu; 
       $this->o55_projativ = $o55_projativ; 
     if(($this->o55_anousu == null) || ($this->o55_anousu == "") ){ 
       $this->erro_sql = " Campo o55_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o55_projativ == null) || ($this->o55_projativ == "") ){ 
       $this->erro_sql = " Campo o55_projativ nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcprojativ(
                                       o55_anousu 
                                      ,o55_tipo 
                                      ,o55_projativ 
                                      ,o55_descr 
                                      ,o55_finali 
                                      ,o55_instit 
                                      ,o55_descrunidade 
                                      ,o55_valorunidade 
                                      ,o55_especproduto 
                                      ,o55_tipoacao 
                                      ,o55_formaimplementacao 
                                      ,o55_detalhamentoimp 
                                      ,o55_origemacao 
                                      ,o55_baselegal 
                                      ,o55_orcproduto 
                       )
                values (
                                $this->o55_anousu 
                               ,$this->o55_tipo 
                               ,$this->o55_projativ 
                               ,'$this->o55_descr' 
                               ,'$this->o55_finali' 
                               ,$this->o55_instit 
                               ,'$this->o55_descrunidade' 
                               ,$this->o55_valorunidade 
                               ,'$this->o55_especproduto' 
                               ,$this->o55_tipoacao 
                               ,$this->o55_formaimplementacao 
                               ,'$this->o55_detalhamentoimp' 
                               ,'$this->o55_origemacao' 
                               ,'$this->o55_baselegal' 
                               ,$this->o55_orcproduto 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Projetos / Atividades ($this->o55_anousu."-".$this->o55_projativ) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Projetos / Atividades já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Projetos / Atividades ($this->o55_anousu."-".$this->o55_projativ) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o55_anousu."-".$this->o55_projativ;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o55_anousu,$this->o55_projativ));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5266,'$this->o55_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,5268,'$this->o55_projativ','I')");
       $resac = db_query("insert into db_acount values($acount,754,5266,'','".AddSlashes(pg_result($resaco,0,'o55_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,754,5267,'','".AddSlashes(pg_result($resaco,0,'o55_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,754,5268,'','".AddSlashes(pg_result($resaco,0,'o55_projativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,754,5269,'','".AddSlashes(pg_result($resaco,0,'o55_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,754,5270,'','".AddSlashes(pg_result($resaco,0,'o55_finali'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,754,5271,'','".AddSlashes(pg_result($resaco,0,'o55_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,754,13672,'','".AddSlashes(pg_result($resaco,0,'o55_descrunidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,754,13673,'','".AddSlashes(pg_result($resaco,0,'o55_valorunidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,754,13674,'','".AddSlashes(pg_result($resaco,0,'o55_especproduto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,754,13675,'','".AddSlashes(pg_result($resaco,0,'o55_tipoacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,754,13676,'','".AddSlashes(pg_result($resaco,0,'o55_formaimplementacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,754,13677,'','".AddSlashes(pg_result($resaco,0,'o55_detalhamentoimp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,754,13678,'','".AddSlashes(pg_result($resaco,0,'o55_origemacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,754,13679,'','".AddSlashes(pg_result($resaco,0,'o55_baselegal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,754,13682,'','".AddSlashes(pg_result($resaco,0,'o55_orcproduto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o55_anousu=null,$o55_projativ=null) { 
      $this->atualizacampos();
     $sql = " update orcprojativ set ";
     $virgula = "";
     if(trim($this->o55_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o55_anousu"])){ 
       $sql  .= $virgula." o55_anousu = $this->o55_anousu ";
       $virgula = ",";
       if(trim($this->o55_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "o55_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o55_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o55_tipo"])){ 
       $sql  .= $virgula." o55_tipo = $this->o55_tipo ";
       $virgula = ",";
       if(trim($this->o55_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "o55_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o55_projativ)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o55_projativ"])){ 
       $sql  .= $virgula." o55_projativ = $this->o55_projativ ";
       $virgula = ",";
       if(trim($this->o55_projativ) == null ){ 
         $this->erro_sql = " Campo Projetos / Atividades nao Informado.";
         $this->erro_campo = "o55_projativ";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o55_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o55_descr"])){ 
       $sql  .= $virgula." o55_descr = '$this->o55_descr' ";
       $virgula = ",";
       if(trim($this->o55_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "o55_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o55_finali)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o55_finali"])){ 
       $sql  .= $virgula." o55_finali = '$this->o55_finali' ";
       $virgula = ",";
     }
     if(trim($this->o55_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o55_instit"])){ 
       $sql  .= $virgula." o55_instit = $this->o55_instit ";
       $virgula = ",";
       if(trim($this->o55_instit) == null ){ 
         $this->erro_sql = " Campo Código da instituicao nao Informado.";
         $this->erro_campo = "o55_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o55_descrunidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o55_descrunidade"])){ 
       $sql  .= $virgula." o55_descrunidade = '$this->o55_descrunidade' ";
       $virgula = ",";
     }
     if(trim($this->o55_valorunidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o55_valorunidade"])){ 
        if(trim($this->o55_valorunidade)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o55_valorunidade"])){ 
           $this->o55_valorunidade = "0" ; 
        } 
       $sql  .= $virgula." o55_valorunidade = $this->o55_valorunidade ";
       $virgula = ",";
     }
     if(trim($this->o55_especproduto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o55_especproduto"])){ 
       $sql  .= $virgula." o55_especproduto = '$this->o55_especproduto' ";
       $virgula = ",";
     }
     if(trim($this->o55_tipoacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o55_tipoacao"])){ 
       $sql  .= $virgula." o55_tipoacao = $this->o55_tipoacao ";
       $virgula = ",";
       if(trim($this->o55_tipoacao) == null ){ 
         $this->erro_sql = " Campo Tipo de Ação nao Informado.";
         $this->erro_campo = "o55_tipoacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o55_formaimplementacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o55_formaimplementacao"])){ 
       $sql  .= $virgula." o55_formaimplementacao = $this->o55_formaimplementacao ";
       $virgula = ",";
       if(trim($this->o55_formaimplementacao) == null ){ 
         $this->erro_sql = " Campo Forma de Implementação nao Informado.";
         $this->erro_campo = "o55_formaimplementacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o55_detalhamentoimp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o55_detalhamentoimp"])){ 
       $sql  .= $virgula." o55_detalhamentoimp = '$this->o55_detalhamentoimp' ";
       $virgula = ",";
     }
     if(trim($this->o55_origemacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o55_origemacao"])){ 
       $sql  .= $virgula." o55_origemacao = '$this->o55_origemacao' ";
       $virgula = ",";
     }
     if(trim($this->o55_baselegal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o55_baselegal"])){ 
       $sql  .= $virgula." o55_baselegal = '$this->o55_baselegal' ";
       $virgula = ",";
     }
     if(trim($this->o55_orcproduto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o55_orcproduto"])){ 
       $sql  .= $virgula." o55_orcproduto = $this->o55_orcproduto ";
       $virgula = ",";
       if(trim($this->o55_orcproduto) == null ){ 
         $this->erro_sql = " Campo Produto nao Informado.";
         $this->erro_campo = "o55_orcproduto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o55_anousu!=null){
       $sql .= " o55_anousu = $this->o55_anousu";
     }
     if($o55_projativ!=null){
       $sql .= " and  o55_projativ = $this->o55_projativ";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o55_anousu,$this->o55_projativ));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5266,'$this->o55_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,5268,'$this->o55_projativ','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o55_anousu"]))
           $resac = db_query("insert into db_acount values($acount,754,5266,'".AddSlashes(pg_result($resaco,$conresaco,'o55_anousu'))."','$this->o55_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o55_tipo"]))
           $resac = db_query("insert into db_acount values($acount,754,5267,'".AddSlashes(pg_result($resaco,$conresaco,'o55_tipo'))."','$this->o55_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o55_projativ"]))
           $resac = db_query("insert into db_acount values($acount,754,5268,'".AddSlashes(pg_result($resaco,$conresaco,'o55_projativ'))."','$this->o55_projativ',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o55_descr"]))
           $resac = db_query("insert into db_acount values($acount,754,5269,'".AddSlashes(pg_result($resaco,$conresaco,'o55_descr'))."','$this->o55_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o55_finali"]))
           $resac = db_query("insert into db_acount values($acount,754,5270,'".AddSlashes(pg_result($resaco,$conresaco,'o55_finali'))."','$this->o55_finali',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o55_instit"]))
           $resac = db_query("insert into db_acount values($acount,754,5271,'".AddSlashes(pg_result($resaco,$conresaco,'o55_instit'))."','$this->o55_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o55_descrunidade"]))
           $resac = db_query("insert into db_acount values($acount,754,13672,'".AddSlashes(pg_result($resaco,$conresaco,'o55_descrunidade'))."','$this->o55_descrunidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o55_valorunidade"]))
           $resac = db_query("insert into db_acount values($acount,754,13673,'".AddSlashes(pg_result($resaco,$conresaco,'o55_valorunidade'))."','$this->o55_valorunidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o55_especproduto"]))
           $resac = db_query("insert into db_acount values($acount,754,13674,'".AddSlashes(pg_result($resaco,$conresaco,'o55_especproduto'))."','$this->o55_especproduto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o55_tipoacao"]))
           $resac = db_query("insert into db_acount values($acount,754,13675,'".AddSlashes(pg_result($resaco,$conresaco,'o55_tipoacao'))."','$this->o55_tipoacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o55_formaimplementacao"]))
           $resac = db_query("insert into db_acount values($acount,754,13676,'".AddSlashes(pg_result($resaco,$conresaco,'o55_formaimplementacao'))."','$this->o55_formaimplementacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o55_detalhamentoimp"]))
           $resac = db_query("insert into db_acount values($acount,754,13677,'".AddSlashes(pg_result($resaco,$conresaco,'o55_detalhamentoimp'))."','$this->o55_detalhamentoimp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o55_origemacao"]))
           $resac = db_query("insert into db_acount values($acount,754,13678,'".AddSlashes(pg_result($resaco,$conresaco,'o55_origemacao'))."','$this->o55_origemacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o55_baselegal"]))
           $resac = db_query("insert into db_acount values($acount,754,13679,'".AddSlashes(pg_result($resaco,$conresaco,'o55_baselegal'))."','$this->o55_baselegal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o55_orcproduto"]))
           $resac = db_query("insert into db_acount values($acount,754,13682,'".AddSlashes(pg_result($resaco,$conresaco,'o55_orcproduto'))."','$this->o55_orcproduto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Projetos / Atividades nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o55_anousu."-".$this->o55_projativ;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Projetos / Atividades nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o55_anousu."-".$this->o55_projativ;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o55_anousu."-".$this->o55_projativ;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o55_anousu=null,$o55_projativ=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o55_anousu,$o55_projativ));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5266,'$o55_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,5268,'$o55_projativ','E')");
         $resac = db_query("insert into db_acount values($acount,754,5266,'','".AddSlashes(pg_result($resaco,$iresaco,'o55_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,754,5267,'','".AddSlashes(pg_result($resaco,$iresaco,'o55_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,754,5268,'','".AddSlashes(pg_result($resaco,$iresaco,'o55_projativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,754,5269,'','".AddSlashes(pg_result($resaco,$iresaco,'o55_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,754,5270,'','".AddSlashes(pg_result($resaco,$iresaco,'o55_finali'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,754,5271,'','".AddSlashes(pg_result($resaco,$iresaco,'o55_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,754,13672,'','".AddSlashes(pg_result($resaco,$iresaco,'o55_descrunidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,754,13673,'','".AddSlashes(pg_result($resaco,$iresaco,'o55_valorunidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,754,13674,'','".AddSlashes(pg_result($resaco,$iresaco,'o55_especproduto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,754,13675,'','".AddSlashes(pg_result($resaco,$iresaco,'o55_tipoacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,754,13676,'','".AddSlashes(pg_result($resaco,$iresaco,'o55_formaimplementacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,754,13677,'','".AddSlashes(pg_result($resaco,$iresaco,'o55_detalhamentoimp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,754,13678,'','".AddSlashes(pg_result($resaco,$iresaco,'o55_origemacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,754,13679,'','".AddSlashes(pg_result($resaco,$iresaco,'o55_baselegal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,754,13682,'','".AddSlashes(pg_result($resaco,$iresaco,'o55_orcproduto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcprojativ
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o55_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o55_anousu = $o55_anousu ";
        }
        if($o55_projativ != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o55_projativ = $o55_projativ ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Projetos / Atividades nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o55_anousu."-".$o55_projativ;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Projetos / Atividades nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o55_anousu."-".$o55_projativ;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o55_anousu."-".$o55_projativ;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcprojativ";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o55_anousu=null,$o55_projativ=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcprojativ ";
     $sql .= "      inner join db_config  on  db_config.codigo = orcprojativ.o55_instit";
     $sql .= "      inner join orcproduto  on  orcproduto.o22_codproduto = orcprojativ.o55_orcproduto";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      left  join orcprojativunidaderesp  on  orcprojativunidaderesp.o13_orcprojativ = orcprojativ.o55_projativ
     												  and  orcprojativunidaderesp.o13_anousu	  = orcprojativ.o55_anousu ";
     $sql .= "      left  join unidaderesp			   on  unidaderesp.o20_sequencial			  = orcprojativunidaderesp.o13_unidaderesp";
     
     $sql2 = "";
     if($dbwhere==""){
       if($o55_anousu!=null ){
         $sql2 .= " where orcprojativ.o55_anousu = $o55_anousu "; 
       } 
       if($o55_projativ!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcprojativ.o55_projativ = $o55_projativ "; 
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
   function sql_query_file ( $o55_anousu=null,$o55_projativ=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcprojativ ";
     $sql2 = "";
     if($dbwhere==""){
       if($o55_anousu!=null ){
         $sql2 .= " where orcprojativ.o55_anousu = $o55_anousu "; 
       } 
       if($o55_projativ!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcprojativ.o55_projativ = $o55_projativ "; 
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
   function sql_query_rh ( $o55_anousu=null,$o55_projativ=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcprojativ ";
     $sql .= "      inner join db_config  on  db_config.codigo = orcprojativ.o55_instit ";
     $sql .= "      inner join orcdotacao on orcdotacao.o58_projativ = orcprojativ.o55_projativ 
                           and orcdotacao.o58_anousu = orcprojativ.o55_anousu ";
     $sql2 = "";
     if($dbwhere==""){
       if($o55_anousu!=null ){
         $sql2 .= " where orcprojativ.o55_anousu = $o55_anousu "; 
       } 
       if($o55_projativ!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcprojativ.o55_projativ = $o55_projativ "; 
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
  
  function sql_query_projetoAtividade($o55_anousu=null,$o55_projativ=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from orcprojativ ";
    $sql .= "      inner join orcproduto on orcproduto.o22_codproduto = orcprojativ.o55_orcproduto ";
    $sql .= "      inner join orcdotacao on orcdotacao.o58_anousu     = orcprojativ.o55_anousu ";
    $sql .= "                           and orcdotacao.o58_projativ   = orcprojativ.o55_projativ ";
    $sql2 = "";
    if($dbwhere=="") {
      
      if($o55_anousu!=null ) {
        $sql2 .= " where orcprojativ.o55_anousu = $o55_anousu ";
      }
      if($o55_projativ!=null ) {
        
        if($sql2!=""){
          $sql2 .= " and ";
        } else {
          $sql2 .= " where ";
        }
        $sql2 .= " orcprojativ.o55_projativ = $o55_projativ ";
     } 
    } else if($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ) {
      
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++) {
        
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
}
?>