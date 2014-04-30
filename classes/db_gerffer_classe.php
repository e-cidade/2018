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
//CLASSE DA ENTIDADE gerffer
class cl_gerffer { 
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
   var $r31_anousu = 0; 
   var $r31_mesusu = 0; 
   var $r31_regist = 0; 
   var $r31_rubric = null; 
   var $r31_valor = 0; 
   var $r31_pd = 0; 
   var $r31_quant = 0; 
   var $r31_lotac = null; 
   var $r31_semest = 0; 
   var $r31_tpp = null; 
   var $r31_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r31_anousu = int4 = Ano do Exercicio 
                 r31_mesusu = int4 = Mes do Exercicio 
                 r31_regist = int4 = Codigo do Funcionario 
                 r31_rubric = char(4) = Rubrica 
                 r31_valor = float8 = Valor da Rubrica 
                 r31_pd = int4 = Indica se e Prov ou Desc. 
                 r31_quant = float8 = Quantidade lancada na Rubrica 
                 r31_lotac = varchar(4) = Lotação 
                 r31_semest = int4 = Semestre 
                 r31_tpp = varchar(1) = Tipo da Rubrica 
                 r31_instit = int4 = codigo da instituicao 
                 ";
   //funcao construtor da classe 
   function cl_gerffer() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("gerffer"); 
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
       $this->r31_anousu = ($this->r31_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r31_anousu"]:$this->r31_anousu);
       $this->r31_mesusu = ($this->r31_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r31_mesusu"]:$this->r31_mesusu);
       $this->r31_regist = ($this->r31_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r31_regist"]:$this->r31_regist);
       $this->r31_rubric = ($this->r31_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r31_rubric"]:$this->r31_rubric);
       $this->r31_valor = ($this->r31_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["r31_valor"]:$this->r31_valor);
       $this->r31_pd = ($this->r31_pd == ""?@$GLOBALS["HTTP_POST_VARS"]["r31_pd"]:$this->r31_pd);
       $this->r31_quant = ($this->r31_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["r31_quant"]:$this->r31_quant);
       $this->r31_lotac = ($this->r31_lotac == ""?@$GLOBALS["HTTP_POST_VARS"]["r31_lotac"]:$this->r31_lotac);
       $this->r31_semest = ($this->r31_semest == ""?@$GLOBALS["HTTP_POST_VARS"]["r31_semest"]:$this->r31_semest);
       $this->r31_tpp = ($this->r31_tpp == ""?@$GLOBALS["HTTP_POST_VARS"]["r31_tpp"]:$this->r31_tpp);
       $this->r31_instit = ($this->r31_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r31_instit"]:$this->r31_instit);
     }else{
       $this->r31_anousu = ($this->r31_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r31_anousu"]:$this->r31_anousu);
       $this->r31_mesusu = ($this->r31_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r31_mesusu"]:$this->r31_mesusu);
       $this->r31_regist = ($this->r31_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r31_regist"]:$this->r31_regist);
       $this->r31_rubric = ($this->r31_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r31_rubric"]:$this->r31_rubric);
       $this->r31_tpp = ($this->r31_tpp == ""?@$GLOBALS["HTTP_POST_VARS"]["r31_tpp"]:$this->r31_tpp);
     }
   }
   // funcao para inclusao
   function incluir ($r31_anousu,$r31_mesusu,$r31_regist,$r31_rubric,$r31_tpp){ 
      $this->atualizacampos();
     if($this->r31_valor == null ){ 
       $this->erro_sql = " Campo Valor da Rubrica nao Informado.";
       $this->erro_campo = "r31_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r31_pd == null ){ 
       $this->erro_sql = " Campo Indica se e Prov ou Desc. nao Informado.";
       $this->erro_campo = "r31_pd";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r31_quant == null ){ 
       $this->erro_sql = " Campo Quantidade lancada na Rubrica nao Informado.";
       $this->erro_campo = "r31_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r31_lotac == null ){ 
       $this->erro_sql = " Campo Lotação nao Informado.";
       $this->erro_campo = "r31_lotac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r31_semest == null ){ 
       $this->erro_sql = " Campo Semestre nao Informado.";
       $this->erro_campo = "r31_semest";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r31_instit == null ){ 
       $this->erro_sql = " Campo codigo da instituicao nao Informado.";
       $this->erro_campo = "r31_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r31_anousu = $r31_anousu; 
       $this->r31_mesusu = $r31_mesusu; 
       $this->r31_regist = $r31_regist; 
       $this->r31_rubric = $r31_rubric; 
       $this->r31_tpp = $r31_tpp; 
     if(($this->r31_anousu == null) || ($this->r31_anousu == "") ){ 
       $this->erro_sql = " Campo r31_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r31_mesusu == null) || ($this->r31_mesusu == "") ){ 
       $this->erro_sql = " Campo r31_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r31_regist == null) || ($this->r31_regist == "") ){ 
       $this->erro_sql = " Campo r31_regist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r31_rubric == null) || ($this->r31_rubric == "") ){ 
       $this->erro_sql = " Campo r31_rubric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r31_tpp == null) || ($this->r31_tpp == "") ){ 
       $this->erro_sql = " Campo r31_tpp nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into gerffer(
                                       r31_anousu 
                                      ,r31_mesusu 
                                      ,r31_regist 
                                      ,r31_rubric 
                                      ,r31_valor 
                                      ,r31_pd 
                                      ,r31_quant 
                                      ,r31_lotac 
                                      ,r31_semest 
                                      ,r31_tpp 
                                      ,r31_instit 
                       )
                values (
                                $this->r31_anousu 
                               ,$this->r31_mesusu 
                               ,$this->r31_regist 
                               ,'$this->r31_rubric' 
                               ,$this->r31_valor 
                               ,$this->r31_pd 
                               ,$this->r31_quant 
                               ,'$this->r31_lotac' 
                               ,$this->r31_semest 
                               ,'$this->r31_tpp' 
                               ,$this->r31_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Financeiro de Ferias ($this->r31_anousu."-".$this->r31_mesusu."-".$this->r31_regist."-".$this->r31_rubric."-".$this->r31_tpp) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Financeiro de Ferias já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Financeiro de Ferias ($this->r31_anousu."-".$this->r31_mesusu."-".$this->r31_regist."-".$this->r31_rubric."-".$this->r31_tpp) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r31_anousu."-".$this->r31_mesusu."-".$this->r31_regist."-".$this->r31_rubric."-".$this->r31_tpp;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r31_anousu,$this->r31_mesusu,$this->r31_regist,$this->r31_rubric,$this->r31_tpp));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,3958,'$this->r31_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,3959,'$this->r31_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,3960,'$this->r31_regist','I')");
       $resac = db_query("insert into db_acountkey values($acount,3961,'$this->r31_rubric','I')");
       $resac = db_query("insert into db_acountkey values($acount,3967,'$this->r31_tpp','I')");
       $resac = db_query("insert into db_acount values($acount,555,3958,'','".AddSlashes(pg_result($resaco,0,'r31_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,555,3959,'','".AddSlashes(pg_result($resaco,0,'r31_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,555,3960,'','".AddSlashes(pg_result($resaco,0,'r31_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,555,3961,'','".AddSlashes(pg_result($resaco,0,'r31_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,555,3962,'','".AddSlashes(pg_result($resaco,0,'r31_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,555,3963,'','".AddSlashes(pg_result($resaco,0,'r31_pd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,555,3964,'','".AddSlashes(pg_result($resaco,0,'r31_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,555,3965,'','".AddSlashes(pg_result($resaco,0,'r31_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,555,3966,'','".AddSlashes(pg_result($resaco,0,'r31_semest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,555,3967,'','".AddSlashes(pg_result($resaco,0,'r31_tpp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,555,7456,'','".AddSlashes(pg_result($resaco,0,'r31_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r31_anousu=null,$r31_mesusu=null,$r31_regist=null,$r31_rubric=null,$r31_tpp=null) { 
      $this->atualizacampos();
     $sql = " update gerffer set ";
     $virgula = "";
     if(trim($this->r31_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r31_anousu"])){ 
       $sql  .= $virgula." r31_anousu = $this->r31_anousu ";
       $virgula = ",";
       if(trim($this->r31_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "r31_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r31_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r31_mesusu"])){ 
       $sql  .= $virgula." r31_mesusu = $this->r31_mesusu ";
       $virgula = ",";
       if(trim($this->r31_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r31_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r31_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r31_regist"])){ 
       $sql  .= $virgula." r31_regist = $this->r31_regist ";
       $virgula = ",";
       if(trim($this->r31_regist) == null ){ 
         $this->erro_sql = " Campo Codigo do Funcionario nao Informado.";
         $this->erro_campo = "r31_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r31_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r31_rubric"])){ 
       $sql  .= $virgula." r31_rubric = '$this->r31_rubric' ";
       $virgula = ",";
       if(trim($this->r31_rubric) == null ){ 
         $this->erro_sql = " Campo Rubrica nao Informado.";
         $this->erro_campo = "r31_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r31_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r31_valor"])){ 
       $sql  .= $virgula." r31_valor = $this->r31_valor ";
       $virgula = ",";
       if(trim($this->r31_valor) == null ){ 
         $this->erro_sql = " Campo Valor da Rubrica nao Informado.";
         $this->erro_campo = "r31_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r31_pd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r31_pd"])){ 
       $sql  .= $virgula." r31_pd = $this->r31_pd ";
       $virgula = ",";
       if(trim($this->r31_pd) == null ){ 
         $this->erro_sql = " Campo Indica se e Prov ou Desc. nao Informado.";
         $this->erro_campo = "r31_pd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r31_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r31_quant"])){ 
       $sql  .= $virgula." r31_quant = $this->r31_quant ";
       $virgula = ",";
       if(trim($this->r31_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade lancada na Rubrica nao Informado.";
         $this->erro_campo = "r31_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r31_lotac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r31_lotac"])){ 
       $sql  .= $virgula." r31_lotac = '$this->r31_lotac' ";
       $virgula = ",";
       if(trim($this->r31_lotac) == null ){ 
         $this->erro_sql = " Campo Lotação nao Informado.";
         $this->erro_campo = "r31_lotac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r31_semest)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r31_semest"])){ 
       $sql  .= $virgula." r31_semest = $this->r31_semest ";
       $virgula = ",";
       if(trim($this->r31_semest) == null ){ 
         $this->erro_sql = " Campo Semestre nao Informado.";
         $this->erro_campo = "r31_semest";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r31_tpp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r31_tpp"])){ 
       $sql  .= $virgula." r31_tpp = '$this->r31_tpp' ";
       $virgula = ",";
       if(trim($this->r31_tpp) == null ){ 
         $this->erro_sql = " Campo Tipo da Rubrica nao Informado.";
         $this->erro_campo = "r31_tpp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r31_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r31_instit"])){ 
       $sql  .= $virgula." r31_instit = $this->r31_instit ";
       $virgula = ",";
       if(trim($this->r31_instit) == null ){ 
         $this->erro_sql = " Campo codigo da instituicao nao Informado.";
         $this->erro_campo = "r31_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r31_anousu!=null){
       $sql .= " r31_anousu = $this->r31_anousu";
     }
     if($r31_mesusu!=null){
       $sql .= " and  r31_mesusu = $this->r31_mesusu";
     }
     if($r31_regist!=null){
       $sql .= " and  r31_regist = $this->r31_regist";
     }
     if($r31_rubric!=null){
       $sql .= " and  r31_rubric = '$this->r31_rubric'";
     }
     if($r31_tpp!=null){
       $sql .= " and  r31_tpp = '$this->r31_tpp'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r31_anousu,$this->r31_mesusu,$this->r31_regist,$this->r31_rubric,$this->r31_tpp));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3958,'$this->r31_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,3959,'$this->r31_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,3960,'$this->r31_regist','A')");
         $resac = db_query("insert into db_acountkey values($acount,3961,'$this->r31_rubric','A')");
         $resac = db_query("insert into db_acountkey values($acount,3967,'$this->r31_tpp','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r31_anousu"]))
           $resac = db_query("insert into db_acount values($acount,555,3958,'".AddSlashes(pg_result($resaco,$conresaco,'r31_anousu'))."','$this->r31_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r31_mesusu"]))
           $resac = db_query("insert into db_acount values($acount,555,3959,'".AddSlashes(pg_result($resaco,$conresaco,'r31_mesusu'))."','$this->r31_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r31_regist"]))
           $resac = db_query("insert into db_acount values($acount,555,3960,'".AddSlashes(pg_result($resaco,$conresaco,'r31_regist'))."','$this->r31_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r31_rubric"]))
           $resac = db_query("insert into db_acount values($acount,555,3961,'".AddSlashes(pg_result($resaco,$conresaco,'r31_rubric'))."','$this->r31_rubric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r31_valor"]))
           $resac = db_query("insert into db_acount values($acount,555,3962,'".AddSlashes(pg_result($resaco,$conresaco,'r31_valor'))."','$this->r31_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r31_pd"]))
           $resac = db_query("insert into db_acount values($acount,555,3963,'".AddSlashes(pg_result($resaco,$conresaco,'r31_pd'))."','$this->r31_pd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r31_quant"]))
           $resac = db_query("insert into db_acount values($acount,555,3964,'".AddSlashes(pg_result($resaco,$conresaco,'r31_quant'))."','$this->r31_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r31_lotac"]))
           $resac = db_query("insert into db_acount values($acount,555,3965,'".AddSlashes(pg_result($resaco,$conresaco,'r31_lotac'))."','$this->r31_lotac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r31_semest"]))
           $resac = db_query("insert into db_acount values($acount,555,3966,'".AddSlashes(pg_result($resaco,$conresaco,'r31_semest'))."','$this->r31_semest',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r31_tpp"]))
           $resac = db_query("insert into db_acount values($acount,555,3967,'".AddSlashes(pg_result($resaco,$conresaco,'r31_tpp'))."','$this->r31_tpp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r31_instit"]))
           $resac = db_query("insert into db_acount values($acount,555,7456,'".AddSlashes(pg_result($resaco,$conresaco,'r31_instit'))."','$this->r31_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Financeiro de Ferias nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r31_anousu."-".$this->r31_mesusu."-".$this->r31_regist."-".$this->r31_rubric."-".$this->r31_tpp;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Financeiro de Ferias nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r31_anousu."-".$this->r31_mesusu."-".$this->r31_regist."-".$this->r31_rubric."-".$this->r31_tpp;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r31_anousu."-".$this->r31_mesusu."-".$this->r31_regist."-".$this->r31_rubric."-".$this->r31_tpp;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r31_anousu=null,$r31_mesusu=null,$r31_regist=null,$r31_rubric=null,$r31_tpp=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r31_anousu,$r31_mesusu,$r31_regist,$r31_rubric,$r31_tpp));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3958,'$r31_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,3959,'$r31_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,3960,'$r31_regist','E')");
         $resac = db_query("insert into db_acountkey values($acount,3961,'$r31_rubric','E')");
         $resac = db_query("insert into db_acountkey values($acount,3967,'$r31_tpp','E')");
         $resac = db_query("insert into db_acount values($acount,555,3958,'','".AddSlashes(pg_result($resaco,$iresaco,'r31_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,555,3959,'','".AddSlashes(pg_result($resaco,$iresaco,'r31_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,555,3960,'','".AddSlashes(pg_result($resaco,$iresaco,'r31_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,555,3961,'','".AddSlashes(pg_result($resaco,$iresaco,'r31_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,555,3962,'','".AddSlashes(pg_result($resaco,$iresaco,'r31_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,555,3963,'','".AddSlashes(pg_result($resaco,$iresaco,'r31_pd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,555,3964,'','".AddSlashes(pg_result($resaco,$iresaco,'r31_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,555,3965,'','".AddSlashes(pg_result($resaco,$iresaco,'r31_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,555,3966,'','".AddSlashes(pg_result($resaco,$iresaco,'r31_semest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,555,3967,'','".AddSlashes(pg_result($resaco,$iresaco,'r31_tpp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,555,7456,'','".AddSlashes(pg_result($resaco,$iresaco,'r31_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from gerffer
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r31_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r31_anousu = $r31_anousu ";
        }
        if($r31_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r31_mesusu = $r31_mesusu ";
        }
        if($r31_regist != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r31_regist = $r31_regist ";
        }
        if($r31_rubric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r31_rubric = '$r31_rubric' ";
        }
        if($r31_tpp != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r31_tpp = '$r31_tpp' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Financeiro de Ferias nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r31_anousu."-".$r31_mesusu."-".$r31_regist."-".$r31_rubric."-".$r31_tpp;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Financeiro de Ferias nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r31_anousu."-".$r31_mesusu."-".$r31_regist."-".$r31_rubric."-".$r31_tpp;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r31_anousu."-".$r31_mesusu."-".$r31_regist."-".$r31_rubric."-".$r31_tpp;
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
        $this->erro_sql   = "Record Vazio na Tabela:gerffer";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $r31_anousu=null,$r31_mesusu=null,$r31_regist=null,$r31_rubric=null,$r31_tpp=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from gerffer ";
     $sql .= "      inner join db_config  on  db_config.codigo = gerffer.r31_instit";
     $sql .= "      inner join lotacao  on  lotacao.r13_anousu = gerffer.r31_anousu 
		                                   and  lotacao.r13_mesusu = gerffer.r31_mesusu 
																			 and  lotacao.r13_codigo = gerffer.r31_lotac
																			 and  lotacao.r13_instit = gerffer.r31_instit ";
     $sql .= "      inner join pessoal  on  pessoal.r01_anousu = gerffer.r31_anousu 
		                                   and  pessoal.r01_mesusu = gerffer.r31_mesusu 
																			 and  pessoal.r01_regist = gerffer.r31_regist
																			 and  pessoal.r01_instit = gerffer.r31_instit ";
     $sql .= "      inner join rubricas  on  rubricas.r06_anousu = gerffer.r31_anousu 
		                                    and  rubricas.r06_mesusu = gerffer.r31_mesusu 
																				and  rubricas.r06_codigo = gerffer.r31_rubric
																				and  rubricas.r06_instit = gerffer.r31_instit ";
     $sql .= "      inner join cgm  as d on   d.z01_numcgm = pessoal.r01_numcgm";
     $sql .= "      inner join funcao  as d on   d.r37_anousu = pessoal.r01_anousu 
		                                       and   d.r37_mesusu = pessoal.r01_mesusu 
																					 and   d.r37_funcao = pessoal.r01_funcao
																					 and   d.r37_instit = pessoal.r01_instit ";
     $sql .= "      inner join inssirf  as d on   d.r33_anousu = pessoal.r01_anousu 
		                                        and   d.r33_mesusu = pessoal.r01_mesusu 
																						and   d.r33_codtab = pessoal.r01_tbprev
																						and   d.r33_instit = pessoal.r01_instit ";
     $sql .= "      inner join cargo  as d on   d.r65_anousu = pessoal.r01_anousu 
		                                      and   d.r65_mesusu = pessoal.r01_mesusu 
																					and   d.r65_cargo = pessoal.r01_cargo";
     $sql2 = "";
     if($dbwhere==""){
       if($r31_anousu!=null ){
         $sql2 .= " where gerffer.r31_anousu = $r31_anousu "; 
       } 
       if($r31_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerffer.r31_mesusu = $r31_mesusu "; 
       } 
       if($r31_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerffer.r31_regist = $r31_regist "; 
       } 
       if($r31_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerffer.r31_rubric = '$r31_rubric' "; 
       } 
       if($r31_tpp!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerffer.r31_tpp = '$r31_tpp' "; 
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
   function sql_query_file ( $r31_anousu=null,$r31_mesusu=null,$r31_regist=null,$r31_rubric=null,$r31_tpp=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from gerffer ";
     $sql2 = "";
     if($dbwhere==""){
       if($r31_anousu!=null ){
         $sql2 .= " where gerffer.r31_anousu = $r31_anousu "; 
       } 
       if($r31_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerffer.r31_mesusu = $r31_mesusu "; 
       } 
       if($r31_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerffer.r31_regist = $r31_regist "; 
       } 
       if($r31_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerffer.r31_rubric = '$r31_rubric' "; 
       } 
       if($r31_tpp!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerffer.r31_tpp = '$r31_tpp' "; 
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
   function sql_query_rhrubricas ( $r31_anousu=null,$r31_mesusu=null,$r31_regist=null,$r31_rubric=null,$r31_tpp=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from gerffer ";
     $sql .= "      inner join rhrubricas  on  rhrubricas.rh27_rubric = gerffer.r31_rubric
		                                      and  rhrubricas.rh27_instit = gerffer.r31_instit ";
     $sql2 = "";
     if($dbwhere==""){
       if($r31_anousu!=null ){
         $sql2 .= " where gerffer.r31_anousu = $r31_anousu "; 
       } 
       if($r31_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerffer.r31_mesusu = $r31_mesusu "; 
       } 
       if($r31_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerffer.r31_regist = $r31_regist "; 
       } 
       if($r31_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerffer.r31_rubric = '$r31_rubric' "; 
       } 
       if($r31_tpp!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerffer.r31_tpp = '$r31_tpp' "; 
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