<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
//CLASSE DA ENTIDADE gerfres
class cl_gerfres { 
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
   var $r20_anousu = 0; 
   var $r20_mesusu = 0; 
   var $r20_regist = 0; 
   var $r20_rubric = null; 
   var $r20_valor = 0; 
   var $r20_pd = 0; 
   var $r20_quant = 0; 
   var $r20_lotac = null; 
   var $r20_semest = 0; 
   var $r20_tpp = null; 
   var $r20_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r20_anousu = int4 = Ano do Exercicio 
                 r20_mesusu = int4 = Mes do Exercicio 
                 r20_regist = int4 = Codigo do Funcionario 
                 r20_rubric = char(4) = Rubrica 
                 r20_valor = float8 = Valor do Ponto Gerado 
                 r20_pd = int4 = Indicador, Prov. e Desconto 
                 r20_quant = float8 = Quantidade de Lancada 
                 r20_lotac = varchar(4) = Lotação 
                 r20_semest = int4 = Semestre 
                 r20_tpp = varchar(1) = Tipo 
                 r20_instit = int4 = codigo da instituicao 
                 ";
   //funcao construtor da classe 
   function cl_gerfres() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("gerfres"); 
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
       $this->r20_anousu = ($this->r20_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r20_anousu"]:$this->r20_anousu);
       $this->r20_mesusu = ($this->r20_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r20_mesusu"]:$this->r20_mesusu);
       $this->r20_regist = ($this->r20_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r20_regist"]:$this->r20_regist);
       $this->r20_rubric = ($this->r20_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r20_rubric"]:$this->r20_rubric);
       $this->r20_valor = ($this->r20_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["r20_valor"]:$this->r20_valor);
       $this->r20_pd = ($this->r20_pd == ""?@$GLOBALS["HTTP_POST_VARS"]["r20_pd"]:$this->r20_pd);
       $this->r20_quant = ($this->r20_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["r20_quant"]:$this->r20_quant);
       $this->r20_lotac = ($this->r20_lotac == ""?@$GLOBALS["HTTP_POST_VARS"]["r20_lotac"]:$this->r20_lotac);
       $this->r20_semest = ($this->r20_semest == ""?@$GLOBALS["HTTP_POST_VARS"]["r20_semest"]:$this->r20_semest);
       $this->r20_tpp = ($this->r20_tpp == ""?@$GLOBALS["HTTP_POST_VARS"]["r20_tpp"]:$this->r20_tpp);
       $this->r20_instit = ($this->r20_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r20_instit"]:$this->r20_instit);
     }else{
       $this->r20_anousu = ($this->r20_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r20_anousu"]:$this->r20_anousu);
       $this->r20_mesusu = ($this->r20_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r20_mesusu"]:$this->r20_mesusu);
       $this->r20_regist = ($this->r20_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r20_regist"]:$this->r20_regist);
       $this->r20_rubric = ($this->r20_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r20_rubric"]:$this->r20_rubric);
       $this->r20_tpp = ($this->r20_tpp == ""?@$GLOBALS["HTTP_POST_VARS"]["r20_tpp"]:$this->r20_tpp);
     }
   }
   // funcao para inclusao
   function incluir ($r20_anousu,$r20_mesusu,$r20_regist,$r20_rubric,$r20_tpp){ 
      $this->atualizacampos();
     if($this->r20_valor == null ){ 
       $this->erro_sql = " Campo Valor do Ponto Gerado nao Informado.";
       $this->erro_campo = "r20_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r20_pd == null ){ 
       $this->erro_sql = " Campo Indicador, Prov. e Desconto nao Informado.";
       $this->erro_campo = "r20_pd";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r20_quant == null ){ 
       $this->erro_sql = " Campo Quantidade de Lancada nao Informado.";
       $this->erro_campo = "r20_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r20_lotac == null ){ 
       $this->erro_sql = " Campo Lotação nao Informado.";
       $this->erro_campo = "r20_lotac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r20_semest == null ){ 
       $this->erro_sql = " Campo Semestre nao Informado.";
       $this->erro_campo = "r20_semest";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r20_instit == null ){ 
       $this->erro_sql = " Campo codigo da instituicao nao Informado.";
       $this->erro_campo = "r20_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r20_anousu = $r20_anousu; 
       $this->r20_mesusu = $r20_mesusu; 
       $this->r20_regist = $r20_regist; 
       $this->r20_rubric = $r20_rubric; 
       $this->r20_tpp = $r20_tpp; 
     if(($this->r20_anousu == null) || ($this->r20_anousu == "") ){ 
       $this->erro_sql = " Campo r20_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r20_mesusu == null) || ($this->r20_mesusu == "") ){ 
       $this->erro_sql = " Campo r20_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r20_regist == null) || ($this->r20_regist == "") ){ 
       $this->erro_sql = " Campo r20_regist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r20_rubric == null) || ($this->r20_rubric == "") ){ 
       $this->erro_sql = " Campo r20_rubric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r20_tpp == null) || ($this->r20_tpp == "") ){ 
       $this->erro_sql = " Campo r20_tpp nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into gerfres(
                                       r20_anousu 
                                      ,r20_mesusu 
                                      ,r20_regist 
                                      ,r20_rubric 
                                      ,r20_valor 
                                      ,r20_pd 
                                      ,r20_quant 
                                      ,r20_lotac 
                                      ,r20_semest 
                                      ,r20_tpp 
                                      ,r20_instit 
                       )
                values (
                                $this->r20_anousu 
                               ,$this->r20_mesusu 
                               ,$this->r20_regist 
                               ,'$this->r20_rubric' 
                               ,$this->r20_valor 
                               ,$this->r20_pd 
                               ,$this->r20_quant 
                               ,'$this->r20_lotac' 
                               ,$this->r20_semest 
                               ,'$this->r20_tpp' 
                               ,$this->r20_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Calculo da Rescisao ($this->r20_anousu."-".$this->r20_mesusu."-".$this->r20_regist."-".$this->r20_rubric."-".$this->r20_tpp) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Calculo da Rescisao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Calculo da Rescisao ($this->r20_anousu."-".$this->r20_mesusu."-".$this->r20_regist."-".$this->r20_rubric."-".$this->r20_tpp) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r20_anousu."-".$this->r20_mesusu."-".$this->r20_regist."-".$this->r20_rubric."-".$this->r20_tpp;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r20_anousu,$this->r20_mesusu,$this->r20_regist,$this->r20_rubric,$this->r20_tpp));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,3977,'$this->r20_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,3978,'$this->r20_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,3979,'$this->r20_regist','I')");
       $resac = db_query("insert into db_acountkey values($acount,3984,'$this->r20_rubric','I')");
       $resac = db_query("insert into db_acountkey values($acount,3986,'$this->r20_tpp','I')");
       $resac = db_query("insert into db_acount values($acount,557,3977,'','".AddSlashes(pg_result($resaco,0,'r20_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,557,3978,'','".AddSlashes(pg_result($resaco,0,'r20_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,557,3979,'','".AddSlashes(pg_result($resaco,0,'r20_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,557,3984,'','".AddSlashes(pg_result($resaco,0,'r20_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,557,3980,'','".AddSlashes(pg_result($resaco,0,'r20_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,557,3981,'','".AddSlashes(pg_result($resaco,0,'r20_pd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,557,3982,'','".AddSlashes(pg_result($resaco,0,'r20_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,557,3983,'','".AddSlashes(pg_result($resaco,0,'r20_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,557,3985,'','".AddSlashes(pg_result($resaco,0,'r20_semest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,557,3986,'','".AddSlashes(pg_result($resaco,0,'r20_tpp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,557,7458,'','".AddSlashes(pg_result($resaco,0,'r20_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r20_anousu=null,$r20_mesusu=null,$r20_regist=null,$r20_rubric=null,$r20_tpp=null) { 
      $this->atualizacampos();
     $sql = " update gerfres set ";
     $virgula = "";
     if(trim($this->r20_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r20_anousu"])){ 
       $sql  .= $virgula." r20_anousu = $this->r20_anousu ";
       $virgula = ",";
       if(trim($this->r20_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "r20_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r20_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r20_mesusu"])){ 
       $sql  .= $virgula." r20_mesusu = $this->r20_mesusu ";
       $virgula = ",";
       if(trim($this->r20_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r20_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r20_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r20_regist"])){ 
       $sql  .= $virgula." r20_regist = $this->r20_regist ";
       $virgula = ",";
       if(trim($this->r20_regist) == null ){ 
         $this->erro_sql = " Campo Codigo do Funcionario nao Informado.";
         $this->erro_campo = "r20_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r20_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r20_rubric"])){ 
       $sql  .= $virgula." r20_rubric = '$this->r20_rubric' ";
       $virgula = ",";
       if(trim($this->r20_rubric) == null ){ 
         $this->erro_sql = " Campo Rubrica nao Informado.";
         $this->erro_campo = "r20_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r20_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r20_valor"])){ 
       $sql  .= $virgula." r20_valor = $this->r20_valor ";
       $virgula = ",";
       if(trim($this->r20_valor) == null ){ 
         $this->erro_sql = " Campo Valor do Ponto Gerado nao Informado.";
         $this->erro_campo = "r20_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r20_pd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r20_pd"])){ 
       $sql  .= $virgula." r20_pd = $this->r20_pd ";
       $virgula = ",";
       if(trim($this->r20_pd) == null ){ 
         $this->erro_sql = " Campo Indicador, Prov. e Desconto nao Informado.";
         $this->erro_campo = "r20_pd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r20_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r20_quant"])){ 
       $sql  .= $virgula." r20_quant = $this->r20_quant ";
       $virgula = ",";
       if(trim($this->r20_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade de Lancada nao Informado.";
         $this->erro_campo = "r20_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r20_lotac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r20_lotac"])){ 
       $sql  .= $virgula." r20_lotac = '$this->r20_lotac' ";
       $virgula = ",";
       if(trim($this->r20_lotac) == null ){ 
         $this->erro_sql = " Campo Lotação nao Informado.";
         $this->erro_campo = "r20_lotac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r20_semest)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r20_semest"])){ 
       $sql  .= $virgula." r20_semest = $this->r20_semest ";
       $virgula = ",";
       if(trim($this->r20_semest) == null ){ 
         $this->erro_sql = " Campo Semestre nao Informado.";
         $this->erro_campo = "r20_semest";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r20_tpp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r20_tpp"])){ 
       $sql  .= $virgula." r20_tpp = '$this->r20_tpp' ";
       $virgula = ",";
       if(trim($this->r20_tpp) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "r20_tpp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r20_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r20_instit"])){ 
       $sql  .= $virgula." r20_instit = $this->r20_instit ";
       $virgula = ",";
       if(trim($this->r20_instit) == null ){ 
         $this->erro_sql = " Campo codigo da instituicao nao Informado.";
         $this->erro_campo = "r20_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r20_anousu!=null){
       $sql .= " r20_anousu = $this->r20_anousu";
     }
     if($r20_mesusu!=null){
       $sql .= " and  r20_mesusu = $this->r20_mesusu";
     }
     if($r20_regist!=null){
       $sql .= " and  r20_regist = $this->r20_regist";
     }
     if($r20_rubric!=null){
       $sql .= " and  r20_rubric = '$this->r20_rubric'";
     }
     if($r20_tpp!=null){
       $sql .= " and  r20_tpp = '$this->r20_tpp'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r20_anousu,$this->r20_mesusu,$this->r20_regist,$this->r20_rubric,$this->r20_tpp));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3977,'$this->r20_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,3978,'$this->r20_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,3979,'$this->r20_regist','A')");
         $resac = db_query("insert into db_acountkey values($acount,3984,'$this->r20_rubric','A')");
         $resac = db_query("insert into db_acountkey values($acount,3986,'$this->r20_tpp','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r20_anousu"]))
           $resac = db_query("insert into db_acount values($acount,557,3977,'".AddSlashes(pg_result($resaco,$conresaco,'r20_anousu'))."','$this->r20_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r20_mesusu"]))
           $resac = db_query("insert into db_acount values($acount,557,3978,'".AddSlashes(pg_result($resaco,$conresaco,'r20_mesusu'))."','$this->r20_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r20_regist"]))
           $resac = db_query("insert into db_acount values($acount,557,3979,'".AddSlashes(pg_result($resaco,$conresaco,'r20_regist'))."','$this->r20_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r20_rubric"]))
           $resac = db_query("insert into db_acount values($acount,557,3984,'".AddSlashes(pg_result($resaco,$conresaco,'r20_rubric'))."','$this->r20_rubric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r20_valor"]))
           $resac = db_query("insert into db_acount values($acount,557,3980,'".AddSlashes(pg_result($resaco,$conresaco,'r20_valor'))."','$this->r20_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r20_pd"]))
           $resac = db_query("insert into db_acount values($acount,557,3981,'".AddSlashes(pg_result($resaco,$conresaco,'r20_pd'))."','$this->r20_pd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r20_quant"]))
           $resac = db_query("insert into db_acount values($acount,557,3982,'".AddSlashes(pg_result($resaco,$conresaco,'r20_quant'))."','$this->r20_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r20_lotac"]))
           $resac = db_query("insert into db_acount values($acount,557,3983,'".AddSlashes(pg_result($resaco,$conresaco,'r20_lotac'))."','$this->r20_lotac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r20_semest"]))
           $resac = db_query("insert into db_acount values($acount,557,3985,'".AddSlashes(pg_result($resaco,$conresaco,'r20_semest'))."','$this->r20_semest',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r20_tpp"]))
           $resac = db_query("insert into db_acount values($acount,557,3986,'".AddSlashes(pg_result($resaco,$conresaco,'r20_tpp'))."','$this->r20_tpp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r20_instit"]))
           $resac = db_query("insert into db_acount values($acount,557,7458,'".AddSlashes(pg_result($resaco,$conresaco,'r20_instit'))."','$this->r20_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Calculo da Rescisao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r20_anousu."-".$this->r20_mesusu."-".$this->r20_regist."-".$this->r20_rubric."-".$this->r20_tpp;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Calculo da Rescisao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r20_anousu."-".$this->r20_mesusu."-".$this->r20_regist."-".$this->r20_rubric."-".$this->r20_tpp;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r20_anousu."-".$this->r20_mesusu."-".$this->r20_regist."-".$this->r20_rubric."-".$this->r20_tpp;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r20_anousu=null,$r20_mesusu=null,$r20_regist=null,$r20_rubric=null,$r20_tpp=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r20_anousu,$r20_mesusu,$r20_regist,$r20_rubric,$r20_tpp));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3977,'$r20_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,3978,'$r20_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,3979,'$r20_regist','E')");
         $resac = db_query("insert into db_acountkey values($acount,3984,'$r20_rubric','E')");
         $resac = db_query("insert into db_acountkey values($acount,3986,'$r20_tpp','E')");
         $resac = db_query("insert into db_acount values($acount,557,3977,'','".AddSlashes(pg_result($resaco,$iresaco,'r20_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,557,3978,'','".AddSlashes(pg_result($resaco,$iresaco,'r20_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,557,3979,'','".AddSlashes(pg_result($resaco,$iresaco,'r20_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,557,3984,'','".AddSlashes(pg_result($resaco,$iresaco,'r20_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,557,3980,'','".AddSlashes(pg_result($resaco,$iresaco,'r20_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,557,3981,'','".AddSlashes(pg_result($resaco,$iresaco,'r20_pd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,557,3982,'','".AddSlashes(pg_result($resaco,$iresaco,'r20_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,557,3983,'','".AddSlashes(pg_result($resaco,$iresaco,'r20_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,557,3985,'','".AddSlashes(pg_result($resaco,$iresaco,'r20_semest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,557,3986,'','".AddSlashes(pg_result($resaco,$iresaco,'r20_tpp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,557,7458,'','".AddSlashes(pg_result($resaco,$iresaco,'r20_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from gerfres
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r20_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r20_anousu = $r20_anousu ";
        }
        if($r20_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r20_mesusu = $r20_mesusu ";
        }
        if($r20_regist != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r20_regist = $r20_regist ";
        }
        if($r20_rubric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r20_rubric = '$r20_rubric' ";
        }
        if($r20_tpp != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r20_tpp = '$r20_tpp' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Calculo da Rescisao nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r20_anousu."-".$r20_mesusu."-".$r20_regist."-".$r20_rubric."-".$r20_tpp;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Calculo da Rescisao nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r20_anousu."-".$r20_mesusu."-".$r20_regist."-".$r20_rubric."-".$r20_tpp;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r20_anousu."-".$r20_mesusu."-".$r20_regist."-".$r20_rubric."-".$r20_tpp;
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
        $this->erro_sql   = "Record Vazio na Tabela:gerfres";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $r20_anousu=null,$r20_mesusu=null,$r20_regist=null,$r20_rubric=null,$r20_tpp=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from gerfres ";
     $sql .= "      inner join db_config  on  db_config.codigo = gerfres.r20_instit";
     $sql .= "      inner join lotacao  on  lotacao.r13_anousu = gerfres.r20_anousu 
		                                   and  lotacao.r13_mesusu = gerfres.r20_mesusu 
																			 and  lotacao.r13_codigo = gerfres.r20_lotac
																			 and  lotacao.r13_instit = gerfres.r20_instit ";
     $sql .= "      inner join pessoal  on  pessoal.r01_anousu = gerfres.r20_anousu 
		                                   and  pessoal.r01_mesusu = gerfres.r20_mesusu 
																			 and  pessoal.r01_regist = gerfres.r20_regist
																			 and  pessoal.r01_instit = gerfres.r20_instit ";
     $sql .= "      inner join rubricas  on  rubricas.r06_anousu = gerfres.r20_anousu 
		                                    and  rubricas.r06_mesusu = gerfres.r20_mesusu 
																				and  rubricas.r06_codigo = gerfres.r20_rubric 
																				and  rubricas.r06_instit = gerfres.r20_instit ";
     $sql .= "      inner join cgm  as d on   d.z01_numcgm = pessoal.r01_numcgm";
     $sql .= "      inner join funcao  as d on   d.r37_anousu = pessoal.r01_anousu 
		                                       and   d.r37_mesusu = pessoal.r01_mesusu 
																					 and   d.r37_funcao = pessoal.r01_funcao
																					 and   d.r37_instit = pessoal.r01_instit ";
     $sql .= "      inner join inssirf  as d on   d.r33_anousu = pessoal.r01_anousu 
		                                        and   d.r33_mesusu = pessoal.r01_mesusu 
																						and   d.r33_codtab = pessoal.r01_tbprev
																						and   d.r33_instit = pessoal.r01_instit ";
     $sql .= "      inner join lotacao  as d on   d.r13_anousu = pessoal.r01_anousu 
		                                        and   d.r13_mesusu = pessoal.r01_mesusu 
																						and   d.r13_codigo = pessoal.r01_lotac
																						and   d.r13_instit = pessoal.r01_instit ";
     $sql .= "      inner join cargo  as d on d.r65_anousu = pessoal.r01_anousu 
		                                      and d.r65_mesusu = pessoal.r01_mesusu 
																					and   d.r65_cargo = pessoal.r01_cargo";
     $sql2 = "";
     if($dbwhere==""){
       if($r20_anousu!=null ){
         $sql2 .= " where gerfres.r20_anousu = $r20_anousu "; 
       } 
       if($r20_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfres.r20_mesusu = $r20_mesusu "; 
       } 
       if($r20_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfres.r20_regist = $r20_regist "; 
       } 
       if($r20_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfres.r20_rubric = '$r20_rubric' "; 
       } 
       if($r20_tpp!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfres.r20_tpp = '$r20_tpp' "; 
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
   function sql_query_file ( $r20_anousu=null,$r20_mesusu=null,$r20_regist=null,$r20_rubric=null,$r20_tpp=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from gerfres ";
     $sql2 = "";
     if($dbwhere==""){
       if($r20_anousu!=null ){
         $sql2 .= " where gerfres.r20_anousu = $r20_anousu "; 
       } 
       if($r20_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfres.r20_mesusu = $r20_mesusu "; 
       } 
       if($r20_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfres.r20_regist = $r20_regist "; 
       } 
       if($r20_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfres.r20_rubric = '$r20_rubric' "; 
       } 
       if($r20_tpp!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfres.r20_tpp = '$r20_tpp' "; 
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
   function sql_query_rhrubricas ( $r20_anousu=null,$r20_mesusu=null,$r20_regist=null,$r20_rubric=null,$r20_tpp=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from gerfres ";
     $sql .= "      inner join rhrubricas  on  rhrubricas.rh27_rubric = gerfres.r20_rubric
		                                      and  rhrubricas.rh27_instit = gerfres.r20_instit ";
     $sql2 = "";
     if($dbwhere==""){
       if($r20_anousu!=null ){
         $sql2 .= " where gerfres.r20_anousu = $r20_anousu "; 
       } 
       if($r20_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfres.r20_mesusu = $r20_mesusu "; 
       } 
       if($r20_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfres.r20_regist = $r20_regist "; 
       } 
       if($r20_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfres.r20_rubric = '$r20_rubric' "; 
       } 
       if($r20_tpp!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfres.r20_tpp = '$r20_tpp' "; 
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
   function sql_query_seleciona ( $r20_anousu=null,$r20_mesusu=null,$r20_regist=null,$r20_rubric=null,$r20_tpp=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from gerfres ";
     $sql .= "      inner join rhpessoal   on  rhpessoal.rh01_regist = gerfres.r20_regist ";
     $sql .= "      inner join rhrubricas  on  rhrubricas.rh27_rubric = gerfres.r20_rubric
		                                      and  rhrubricas.rh27_instit = gerfres.r20_instit ";
     $sql .= "      inner join rhlota      on  rhlota.r70_codigo = to_number(gerfres.r20_lotac, '9999')::integer
		                                      and  rhlota.r70_instit = gerfres.r20_instit ";
     $sql .= "      inner join cgm         on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($r20_anousu!=null ){
         $sql2 .= " where gerfres.r20_anousu = $r20_anousu "; 
       } 
       if($r20_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfres.r20_mesusu = $r20_mesusu "; 
       } 
       if($r20_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfres.r20_regist = $r20_regist "; 
       } 
       if($r20_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfres.r20_rubric = '$r20_rubric' "; 
       } 
       if($r20_tpp!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfres.r20_tpp = '$r20_tpp' "; 
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

  public function migraGerfRes($iInstituicao) {

    $sSql  = "create table w_migracao_rescisao as select distinct r19_anousu,                                                    ";
    $sSql .= "                                                         r19_mesusu,                                               ";
    $sSql .= "                                                         r19_instit                                                ";
    $sSql .= "                                      from pontofr                                                                 ";
    $sSql .= "                                     inner join gerfres on r19_anousu = r20_anousu                                 ";
    $sSql .= "                                                       and r19_mesusu = r20_mesusu                                 ";
    $sSql .= "                                                       and r19_instit = {$iInstituicao};                           ";
    $sSql .= "                                                                                                                   ";
    
    $sSql .= "insert into rhfolhapagamento                                                                                       ";
    $sSql .= "select nextval('rhfolhapagamento_rh141_sequencial_seq'),                                                           ";
    $sSql .= "       0,                                                                                                          ";
    $sSql .= "       r19_anousu,                                                                                                 ";
    $sSql .= "       r19_mesusu,                                                                                                 ";
    $sSql .= "       r19_anousu,                                                                                                 ";
    $sSql .= "       r19_mesusu,                                                                                                 ";
    $sSql .= "       r19_instit,                                                                                                 ";
    $sSql .= "       2,                                                                                                          ";
    $sSql .= "       false,                                                                                                      ";
    $sSql .= "       'Folha Rescisão número: 0 da competência: ' || r19_anousu || '/' || r19_mesusu || ' gerada automaticamente.'";
    $sSql .= "  from w_migracao_rescisao                                                                                         ";
    $sSql .= "  order by r19_anousu asc,                                                                                         ";
    $sSql .= "           r19_mesusu asc;                                                                                         ";

    return $sSql;      
  }

}
