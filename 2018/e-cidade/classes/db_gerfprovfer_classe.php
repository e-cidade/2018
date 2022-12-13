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

//MODULO: pessoal
//CLASSE DA ENTIDADE gerfprovfer
class cl_gerfprovfer { 
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
   var $r93_anousu = 0; 
   var $r93_mesusu = 0; 
   var $r93_regist = 0; 
   var $r93_rubric = null; 
   var $r93_valor = 0; 
   var $r93_pd = 0; 
   var $r93_quant = 0; 
   var $r93_lotac = null; 
   var $r93_semest = 0; 
   var $r93_tpp = null; 
   var $r93_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r93_anousu = int4 = Ano do Exercicio 
                 r93_mesusu = int4 = Mes do Exercicio 
                 r93_regist = int4 = Codigo do Funcionario 
                 r93_rubric = char(4) = Rubrica 
                 r93_valor = float8 = Valor da Rubrica 
                 r93_pd = int4 = Indica se e Prov ou Desc. 
                 r93_quant = float8 = Quantidade lancada na Rubrica 
                 r93_lotac = char(4) = Lotação 
                 r93_semest = int4 = Semestre 
                 r93_tpp = char(1) = Tipo da Rubrica 
                 r93_instit = int4 = codigo da instituicao 
                 ";
   //funcao construtor da classe 
   function cl_gerfprovfer() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("gerfprovfer"); 
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
       $this->r93_anousu = ($this->r93_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r93_anousu"]:$this->r93_anousu);
       $this->r93_mesusu = ($this->r93_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r93_mesusu"]:$this->r93_mesusu);
       $this->r93_regist = ($this->r93_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r93_regist"]:$this->r93_regist);
       $this->r93_rubric = ($this->r93_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r93_rubric"]:$this->r93_rubric);
       $this->r93_valor = ($this->r93_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["r93_valor"]:$this->r93_valor);
       $this->r93_pd = ($this->r93_pd == ""?@$GLOBALS["HTTP_POST_VARS"]["r93_pd"]:$this->r93_pd);
       $this->r93_quant = ($this->r93_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["r93_quant"]:$this->r93_quant);
       $this->r93_lotac = ($this->r93_lotac == ""?@$GLOBALS["HTTP_POST_VARS"]["r93_lotac"]:$this->r93_lotac);
       $this->r93_semest = ($this->r93_semest == ""?@$GLOBALS["HTTP_POST_VARS"]["r93_semest"]:$this->r93_semest);
       $this->r93_tpp = ($this->r93_tpp == ""?@$GLOBALS["HTTP_POST_VARS"]["r93_tpp"]:$this->r93_tpp);
       $this->r93_instit = ($this->r93_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r93_instit"]:$this->r93_instit);
     }else{
       $this->r93_anousu = ($this->r93_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r93_anousu"]:$this->r93_anousu);
       $this->r93_mesusu = ($this->r93_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r93_mesusu"]:$this->r93_mesusu);
       $this->r93_regist = ($this->r93_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r93_regist"]:$this->r93_regist);
       $this->r93_rubric = ($this->r93_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r93_rubric"]:$this->r93_rubric);
       $this->r93_tpp = ($this->r93_tpp == ""?@$GLOBALS["HTTP_POST_VARS"]["r93_tpp"]:$this->r93_tpp);
     }
   }
   // funcao para inclusao
   function incluir ($r93_anousu,$r93_mesusu,$r93_regist,$r93_rubric,$r93_tpp){ 
      $this->atualizacampos();
     if($this->r93_valor == null ){ 
       $this->erro_sql = " Campo Valor da Rubrica nao Informado.";
       $this->erro_campo = "r93_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r93_pd == null ){ 
       $this->erro_sql = " Campo Indica se e Prov ou Desc. nao Informado.";
       $this->erro_campo = "r93_pd";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r93_quant == null ){ 
       $this->erro_sql = " Campo Quantidade lancada na Rubrica nao Informado.";
       $this->erro_campo = "r93_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r93_lotac == null ){ 
       $this->erro_sql = " Campo Lotação nao Informado.";
       $this->erro_campo = "r93_lotac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r93_semest == null ){ 
       $this->erro_sql = " Campo Semestre nao Informado.";
       $this->erro_campo = "r93_semest";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r93_instit == null ){ 
       $this->erro_sql = " Campo codigo da instituicao nao Informado.";
       $this->erro_campo = "r93_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r93_anousu = $r93_anousu; 
       $this->r93_mesusu = $r93_mesusu; 
       $this->r93_regist = $r93_regist; 
       $this->r93_rubric = $r93_rubric; 
       $this->r93_tpp = $r93_tpp; 
     if(($this->r93_anousu == null) || ($this->r93_anousu == "") ){ 
       $this->erro_sql = " Campo r93_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r93_mesusu == null) || ($this->r93_mesusu == "") ){ 
       $this->erro_sql = " Campo r93_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r93_regist == null) || ($this->r93_regist == "") ){ 
       $this->erro_sql = " Campo r93_regist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r93_rubric == null) || ($this->r93_rubric == "") ){ 
       $this->erro_sql = " Campo r93_rubric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r93_tpp == null) || ($this->r93_tpp == "") ){ 
       $this->erro_sql = " Campo r93_tpp nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into gerfprovfer(
                                       r93_anousu 
                                      ,r93_mesusu 
                                      ,r93_regist 
                                      ,r93_rubric 
                                      ,r93_valor 
                                      ,r93_pd 
                                      ,r93_quant 
                                      ,r93_lotac 
                                      ,r93_semest 
                                      ,r93_tpp 
                                      ,r93_instit 
                       )
                values (
                                $this->r93_anousu 
                               ,$this->r93_mesusu 
                               ,$this->r93_regist 
                               ,'$this->r93_rubric' 
                               ,$this->r93_valor 
                               ,$this->r93_pd 
                               ,$this->r93_quant 
                               ,'$this->r93_lotac' 
                               ,$this->r93_semest 
                               ,'$this->r93_tpp' 
                               ,$this->r93_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Financeiro de Provisao de Ferias ($this->r93_anousu."-".$this->r93_mesusu."-".$this->r93_regist."-".$this->r93_rubric."-".$this->r93_tpp) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Financeiro de Provisao de Ferias já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Financeiro de Provisao de Ferias ($this->r93_anousu."-".$this->r93_mesusu."-".$this->r93_regist."-".$this->r93_rubric."-".$this->r93_tpp) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r93_anousu."-".$this->r93_mesusu."-".$this->r93_regist."-".$this->r93_rubric."-".$this->r93_tpp;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r93_anousu,$this->r93_mesusu,$this->r93_regist,$this->r93_rubric,$this->r93_tpp));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,13315,'$this->r93_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,13316,'$this->r93_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,13317,'$this->r93_regist','I')");
       $resac = db_query("insert into db_acountkey values($acount,13318,'$this->r93_rubric','I')");
       $resac = db_query("insert into db_acountkey values($acount,13324,'$this->r93_tpp','I')");
       $resac = db_query("insert into db_acount values($acount,2332,13315,'','".AddSlashes(pg_result($resaco,0,'r93_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2332,13316,'','".AddSlashes(pg_result($resaco,0,'r93_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2332,13317,'','".AddSlashes(pg_result($resaco,0,'r93_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2332,13318,'','".AddSlashes(pg_result($resaco,0,'r93_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2332,13319,'','".AddSlashes(pg_result($resaco,0,'r93_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2332,13320,'','".AddSlashes(pg_result($resaco,0,'r93_pd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2332,13321,'','".AddSlashes(pg_result($resaco,0,'r93_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2332,13322,'','".AddSlashes(pg_result($resaco,0,'r93_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2332,13323,'','".AddSlashes(pg_result($resaco,0,'r93_semest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2332,13324,'','".AddSlashes(pg_result($resaco,0,'r93_tpp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2332,13325,'','".AddSlashes(pg_result($resaco,0,'r93_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r93_anousu=null,$r93_mesusu=null,$r93_regist=null,$r93_rubric=null,$r93_tpp=null) { 
      $this->atualizacampos();
     $sql = " update gerfprovfer set ";
     $virgula = "";
     if(trim($this->r93_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r93_anousu"])){ 
       $sql  .= $virgula." r93_anousu = $this->r93_anousu ";
       $virgula = ",";
       if(trim($this->r93_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "r93_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r93_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r93_mesusu"])){ 
       $sql  .= $virgula." r93_mesusu = $this->r93_mesusu ";
       $virgula = ",";
       if(trim($this->r93_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r93_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r93_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r93_regist"])){ 
       $sql  .= $virgula." r93_regist = $this->r93_regist ";
       $virgula = ",";
       if(trim($this->r93_regist) == null ){ 
         $this->erro_sql = " Campo Codigo do Funcionario nao Informado.";
         $this->erro_campo = "r93_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r93_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r93_rubric"])){ 
       $sql  .= $virgula." r93_rubric = '$this->r93_rubric' ";
       $virgula = ",";
       if(trim($this->r93_rubric) == null ){ 
         $this->erro_sql = " Campo Rubrica nao Informado.";
         $this->erro_campo = "r93_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r93_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r93_valor"])){ 
       $sql  .= $virgula." r93_valor = $this->r93_valor ";
       $virgula = ",";
       if(trim($this->r93_valor) == null ){ 
         $this->erro_sql = " Campo Valor da Rubrica nao Informado.";
         $this->erro_campo = "r93_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r93_pd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r93_pd"])){ 
       $sql  .= $virgula." r93_pd = $this->r93_pd ";
       $virgula = ",";
       if(trim($this->r93_pd) == null ){ 
         $this->erro_sql = " Campo Indica se e Prov ou Desc. nao Informado.";
         $this->erro_campo = "r93_pd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r93_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r93_quant"])){ 
       $sql  .= $virgula." r93_quant = $this->r93_quant ";
       $virgula = ",";
       if(trim($this->r93_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade lancada na Rubrica nao Informado.";
         $this->erro_campo = "r93_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r93_lotac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r93_lotac"])){ 
       $sql  .= $virgula." r93_lotac = '$this->r93_lotac' ";
       $virgula = ",";
       if(trim($this->r93_lotac) == null ){ 
         $this->erro_sql = " Campo Lotação nao Informado.";
         $this->erro_campo = "r93_lotac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r93_semest)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r93_semest"])){ 
       $sql  .= $virgula." r93_semest = $this->r93_semest ";
       $virgula = ",";
       if(trim($this->r93_semest) == null ){ 
         $this->erro_sql = " Campo Semestre nao Informado.";
         $this->erro_campo = "r93_semest";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r93_tpp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r93_tpp"])){ 
       $sql  .= $virgula." r93_tpp = '$this->r93_tpp' ";
       $virgula = ",";
       if(trim($this->r93_tpp) == null ){ 
         $this->erro_sql = " Campo Tipo da Rubrica nao Informado.";
         $this->erro_campo = "r93_tpp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r93_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r93_instit"])){ 
       $sql  .= $virgula." r93_instit = $this->r93_instit ";
       $virgula = ",";
       if(trim($this->r93_instit) == null ){ 
         $this->erro_sql = " Campo codigo da instituicao nao Informado.";
         $this->erro_campo = "r93_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r93_anousu!=null){
       $sql .= " r93_anousu = $this->r93_anousu";
     }
     if($r93_mesusu!=null){
       $sql .= " and  r93_mesusu = $this->r93_mesusu";
     }
     if($r93_regist!=null){
       $sql .= " and  r93_regist = $this->r93_regist";
     }
     if($r93_rubric!=null){
       $sql .= " and  r93_rubric = '$this->r93_rubric'";
     }
     if($r93_tpp!=null){
       $sql .= " and  r93_tpp = '$this->r93_tpp'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r93_anousu,$this->r93_mesusu,$this->r93_regist,$this->r93_rubric,$this->r93_tpp));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13315,'$this->r93_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,13316,'$this->r93_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,13317,'$this->r93_regist','A')");
         $resac = db_query("insert into db_acountkey values($acount,13318,'$this->r93_rubric','A')");
         $resac = db_query("insert into db_acountkey values($acount,13324,'$this->r93_tpp','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r93_anousu"]) || $this->r93_anousu != "")
           $resac = db_query("insert into db_acount values($acount,2332,13315,'".AddSlashes(pg_result($resaco,$conresaco,'r93_anousu'))."','$this->r93_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r93_mesusu"]) || $this->r93_mesusu != "")
           $resac = db_query("insert into db_acount values($acount,2332,13316,'".AddSlashes(pg_result($resaco,$conresaco,'r93_mesusu'))."','$this->r93_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r93_regist"]) || $this->r93_regist != "")
           $resac = db_query("insert into db_acount values($acount,2332,13317,'".AddSlashes(pg_result($resaco,$conresaco,'r93_regist'))."','$this->r93_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r93_rubric"]) || $this->r93_rubric != "")
           $resac = db_query("insert into db_acount values($acount,2332,13318,'".AddSlashes(pg_result($resaco,$conresaco,'r93_rubric'))."','$this->r93_rubric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r93_valor"]) || $this->r93_valor != "")
           $resac = db_query("insert into db_acount values($acount,2332,13319,'".AddSlashes(pg_result($resaco,$conresaco,'r93_valor'))."','$this->r93_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r93_pd"]) || $this->r93_pd != "")
           $resac = db_query("insert into db_acount values($acount,2332,13320,'".AddSlashes(pg_result($resaco,$conresaco,'r93_pd'))."','$this->r93_pd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r93_quant"]) || $this->r93_quant != "")
           $resac = db_query("insert into db_acount values($acount,2332,13321,'".AddSlashes(pg_result($resaco,$conresaco,'r93_quant'))."','$this->r93_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r93_lotac"]) || $this->r93_lotac != "")
           $resac = db_query("insert into db_acount values($acount,2332,13322,'".AddSlashes(pg_result($resaco,$conresaco,'r93_lotac'))."','$this->r93_lotac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r93_semest"]) || $this->r93_semest != "")
           $resac = db_query("insert into db_acount values($acount,2332,13323,'".AddSlashes(pg_result($resaco,$conresaco,'r93_semest'))."','$this->r93_semest',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r93_tpp"]) || $this->r93_tpp != "")
           $resac = db_query("insert into db_acount values($acount,2332,13324,'".AddSlashes(pg_result($resaco,$conresaco,'r93_tpp'))."','$this->r93_tpp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r93_instit"]) || $this->r93_instit != "")
           $resac = db_query("insert into db_acount values($acount,2332,13325,'".AddSlashes(pg_result($resaco,$conresaco,'r93_instit'))."','$this->r93_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Financeiro de Provisao de Ferias nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r93_anousu."-".$this->r93_mesusu."-".$this->r93_regist."-".$this->r93_rubric."-".$this->r93_tpp;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Financeiro de Provisao de Ferias nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r93_anousu."-".$this->r93_mesusu."-".$this->r93_regist."-".$this->r93_rubric."-".$this->r93_tpp;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r93_anousu."-".$this->r93_mesusu."-".$this->r93_regist."-".$this->r93_rubric."-".$this->r93_tpp;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r93_anousu=null,$r93_mesusu=null,$r93_regist=null,$r93_rubric=null,$r93_tpp=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r93_anousu,$r93_mesusu,$r93_regist,$r93_rubric,$r93_tpp));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13315,'$r93_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,13316,'$r93_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,13317,'$r93_regist','E')");
         $resac = db_query("insert into db_acountkey values($acount,13318,'$r93_rubric','E')");
         $resac = db_query("insert into db_acountkey values($acount,13324,'$r93_tpp','E')");
         $resac = db_query("insert into db_acount values($acount,2332,13315,'','".AddSlashes(pg_result($resaco,$iresaco,'r93_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2332,13316,'','".AddSlashes(pg_result($resaco,$iresaco,'r93_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2332,13317,'','".AddSlashes(pg_result($resaco,$iresaco,'r93_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2332,13318,'','".AddSlashes(pg_result($resaco,$iresaco,'r93_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2332,13319,'','".AddSlashes(pg_result($resaco,$iresaco,'r93_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2332,13320,'','".AddSlashes(pg_result($resaco,$iresaco,'r93_pd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2332,13321,'','".AddSlashes(pg_result($resaco,$iresaco,'r93_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2332,13322,'','".AddSlashes(pg_result($resaco,$iresaco,'r93_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2332,13323,'','".AddSlashes(pg_result($resaco,$iresaco,'r93_semest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2332,13324,'','".AddSlashes(pg_result($resaco,$iresaco,'r93_tpp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2332,13325,'','".AddSlashes(pg_result($resaco,$iresaco,'r93_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from gerfprovfer
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r93_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r93_anousu = $r93_anousu ";
        }
        if($r93_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r93_mesusu = $r93_mesusu ";
        }
        if($r93_regist != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r93_regist = $r93_regist ";
        }
        if($r93_rubric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r93_rubric = '$r93_rubric' ";
        }
        if($r93_tpp != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r93_tpp = '$r93_tpp' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Financeiro de Provisao de Ferias nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r93_anousu."-".$r93_mesusu."-".$r93_regist."-".$r93_rubric."-".$r93_tpp;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Financeiro de Provisao de Ferias nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r93_anousu."-".$r93_mesusu."-".$r93_regist."-".$r93_rubric."-".$r93_tpp;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r93_anousu."-".$r93_mesusu."-".$r93_regist."-".$r93_rubric."-".$r93_tpp;
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
        $this->erro_sql   = "Record Vazio na Tabela:gerfprovfer";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $r93_anousu=null,$r93_mesusu=null,$r93_regist=null,$r93_rubric=null,$r93_tpp=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from gerfprovfer ";
     $sql .= "      inner join db_config  on  db_config.codigo = gerfprovfer.r93_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if($dbwhere==""){
       if($r93_anousu!=null ){
         $sql2 .= " where gerfprovfer.r93_anousu = $r93_anousu "; 
       } 
       if($r93_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfprovfer.r93_mesusu = $r93_mesusu "; 
       } 
       if($r93_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfprovfer.r93_regist = $r93_regist "; 
       } 
       if($r93_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfprovfer.r93_rubric = '$r93_rubric' "; 
       } 
       if($r93_tpp!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfprovfer.r93_tpp = '$r93_tpp' "; 
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
   function sql_query_file ( $r93_anousu=null,$r93_mesusu=null,$r93_regist=null,$r93_rubric=null,$r93_tpp=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from gerfprovfer ";
     $sql2 = "";
     if($dbwhere==""){
       if($r93_anousu!=null ){
         $sql2 .= " where gerfprovfer.r93_anousu = $r93_anousu "; 
       } 
       if($r93_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfprovfer.r93_mesusu = $r93_mesusu "; 
       } 
       if($r93_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfprovfer.r93_regist = $r93_regist "; 
       } 
       if($r93_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfprovfer.r93_rubric = '$r93_rubric' "; 
       } 
       if($r93_tpp!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfprovfer.r93_tpp = '$r93_tpp' "; 
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
}
?>