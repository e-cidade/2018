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
//CLASSE DA ENTIDADE pontofe
class cl_pontofe { 
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
   var $r29_anousu = 0; 
   var $r29_mesusu = 0; 
   var $r29_regist = 0; 
   var $r29_rubric = null; 
   var $r29_valor = 0; 
   var $r29_quant = 0; 
   var $r29_lotac = null; 
   var $r29_media = 0; 
   var $r29_calc = 0; 
   var $r29_tpp = null; 
   var $r29_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r29_anousu = int4 = Ano do Exercicio 
                 r29_mesusu = int4 = Mes do Exercicio 
                 r29_regist = int4 = Matrícula 
                 r29_rubric = char(4) = Rubrica 
                 r29_valor = float8 = Valor 
                 r29_quant = float8 = Quantidade 
                 r29_lotac = char(4) = Lotação 
                 r29_media = int4 = Numero meses incidido na ficha 
                 r29_calc = int4 = Formula de Calculo 
                 r29_tpp = char(1) = Tipo 
                 r29_instit = int4 = codigo da instituicao 
                 ";
   //funcao construtor da classe 
   function cl_pontofe() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pontofe"); 
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
       $this->r29_anousu = ($this->r29_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r29_anousu"]:$this->r29_anousu);
       $this->r29_mesusu = ($this->r29_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r29_mesusu"]:$this->r29_mesusu);
       $this->r29_regist = ($this->r29_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r29_regist"]:$this->r29_regist);
       $this->r29_rubric = ($this->r29_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r29_rubric"]:$this->r29_rubric);
       $this->r29_valor = ($this->r29_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["r29_valor"]:$this->r29_valor);
       $this->r29_quant = ($this->r29_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["r29_quant"]:$this->r29_quant);
       $this->r29_lotac = ($this->r29_lotac == ""?@$GLOBALS["HTTP_POST_VARS"]["r29_lotac"]:$this->r29_lotac);
       $this->r29_media = ($this->r29_media == ""?@$GLOBALS["HTTP_POST_VARS"]["r29_media"]:$this->r29_media);
       $this->r29_calc = ($this->r29_calc == ""?@$GLOBALS["HTTP_POST_VARS"]["r29_calc"]:$this->r29_calc);
       $this->r29_tpp = ($this->r29_tpp == ""?@$GLOBALS["HTTP_POST_VARS"]["r29_tpp"]:$this->r29_tpp);
       $this->r29_instit = ($this->r29_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r29_instit"]:$this->r29_instit);
     }else{
       $this->r29_anousu = ($this->r29_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r29_anousu"]:$this->r29_anousu);
       $this->r29_mesusu = ($this->r29_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r29_mesusu"]:$this->r29_mesusu);
       $this->r29_regist = ($this->r29_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r29_regist"]:$this->r29_regist);
       $this->r29_rubric = ($this->r29_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r29_rubric"]:$this->r29_rubric);
       $this->r29_tpp = ($this->r29_tpp == ""?@$GLOBALS["HTTP_POST_VARS"]["r29_tpp"]:$this->r29_tpp);
     }
   }
   // funcao para inclusao
   function incluir ($r29_anousu,$r29_mesusu,$r29_regist,$r29_rubric,$r29_tpp){ 
      $this->atualizacampos();
     if($this->r29_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "r29_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r29_quant == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "r29_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r29_lotac == null ){ 
       $this->erro_sql = " Campo Lotação nao Informado.";
       $this->erro_campo = "r29_lotac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r29_media == null ){ 
       $this->erro_sql = " Campo Numero meses incidido na ficha nao Informado.";
       $this->erro_campo = "r29_media";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r29_calc == null ){ 
       $this->erro_sql = " Campo Formula de Calculo nao Informado.";
       $this->erro_campo = "r29_calc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r29_instit == null ){ 
       $this->erro_sql = " Campo codigo da instituicao nao Informado.";
       $this->erro_campo = "r29_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r29_anousu = $r29_anousu; 
       $this->r29_mesusu = $r29_mesusu; 
       $this->r29_regist = $r29_regist; 
       $this->r29_rubric = $r29_rubric; 
       $this->r29_tpp = $r29_tpp; 
     if(($this->r29_anousu == null) || ($this->r29_anousu == "") ){ 
       $this->erro_sql = " Campo r29_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r29_mesusu == null) || ($this->r29_mesusu == "") ){ 
       $this->erro_sql = " Campo r29_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r29_regist == null) || ($this->r29_regist == "") ){ 
       $this->erro_sql = " Campo r29_regist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r29_rubric == null) || ($this->r29_rubric == "") ){ 
       $this->erro_sql = " Campo r29_rubric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r29_tpp == null) || ($this->r29_tpp == "") ){ 
       $this->erro_sql = " Campo r29_tpp nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pontofe(
                                       r29_anousu 
                                      ,r29_mesusu 
                                      ,r29_regist 
                                      ,r29_rubric 
                                      ,r29_valor 
                                      ,r29_quant 
                                      ,r29_lotac 
                                      ,r29_media 
                                      ,r29_calc 
                                      ,r29_tpp 
                                      ,r29_instit 
                       )
                values (
                                $this->r29_anousu 
                               ,$this->r29_mesusu 
                               ,$this->r29_regist 
                               ,'$this->r29_rubric' 
                               ,$this->r29_valor 
                               ,$this->r29_quant 
                               ,'$this->r29_lotac' 
                               ,$this->r29_media 
                               ,$this->r29_calc 
                               ,'$this->r29_tpp' 
                               ,$this->r29_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ponto das Ferias ($this->r29_anousu."-".$this->r29_mesusu."-".$this->r29_regist."-".$this->r29_rubric."-".$this->r29_tpp) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ponto das Ferias já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ponto das Ferias ($this->r29_anousu."-".$this->r29_mesusu."-".$this->r29_regist."-".$this->r29_rubric."-".$this->r29_tpp) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r29_anousu."-".$this->r29_mesusu."-".$this->r29_regist."-".$this->r29_rubric."-".$this->r29_tpp;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r29_anousu,$this->r29_mesusu,$this->r29_regist,$this->r29_rubric,$this->r29_tpp));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4297,'$this->r29_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4298,'$this->r29_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4299,'$this->r29_regist','I')");
       $resac = db_query("insert into db_acountkey values($acount,4300,'$this->r29_rubric','I')");
       $resac = db_query("insert into db_acountkey values($acount,4306,'$this->r29_tpp','I')");
       $resac = db_query("insert into db_acount values($acount,577,4297,'','".AddSlashes(pg_result($resaco,0,'r29_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,577,4298,'','".AddSlashes(pg_result($resaco,0,'r29_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,577,4299,'','".AddSlashes(pg_result($resaco,0,'r29_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,577,4300,'','".AddSlashes(pg_result($resaco,0,'r29_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,577,4301,'','".AddSlashes(pg_result($resaco,0,'r29_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,577,4302,'','".AddSlashes(pg_result($resaco,0,'r29_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,577,4303,'','".AddSlashes(pg_result($resaco,0,'r29_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,577,4304,'','".AddSlashes(pg_result($resaco,0,'r29_media'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,577,4305,'','".AddSlashes(pg_result($resaco,0,'r29_calc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,577,4306,'','".AddSlashes(pg_result($resaco,0,'r29_tpp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,577,7464,'','".AddSlashes(pg_result($resaco,0,'r29_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r29_anousu=null,$r29_mesusu=null,$r29_regist=null,$r29_rubric=null,$r29_tpp=null,$where="") { 
      $this->atualizacampos();
     $sql = " update pontofe set ";
     $virgula = "";
     if(trim($this->r29_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r29_anousu"])){ 
       $sql  .= $virgula." r29_anousu = $this->r29_anousu ";
       $virgula = ",";
       if(trim($this->r29_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "r29_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r29_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r29_mesusu"])){ 
       $sql  .= $virgula." r29_mesusu = $this->r29_mesusu ";
       $virgula = ",";
       if(trim($this->r29_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r29_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r29_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r29_regist"])){ 
       $sql  .= $virgula." r29_regist = $this->r29_regist ";
       $virgula = ",";
       if(trim($this->r29_regist) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "r29_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r29_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r29_rubric"])){ 
       $sql  .= $virgula." r29_rubric = '$this->r29_rubric' ";
       $virgula = ",";
       if(trim($this->r29_rubric) == null ){ 
         $this->erro_sql = " Campo Rubrica nao Informado.";
         $this->erro_campo = "r29_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r29_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r29_valor"])){ 
       $sql  .= $virgula." r29_valor = $this->r29_valor ";
       $virgula = ",";
       if(trim($this->r29_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "r29_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r29_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r29_quant"])){ 
       $sql  .= $virgula." r29_quant = $this->r29_quant ";
       $virgula = ",";
       if(trim($this->r29_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "r29_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r29_lotac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r29_lotac"])){ 
       $sql  .= $virgula." r29_lotac = '$this->r29_lotac' ";
       $virgula = ",";
       if(trim($this->r29_lotac) == null ){ 
         $this->erro_sql = " Campo Lotação nao Informado.";
         $this->erro_campo = "r29_lotac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r29_media)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r29_media"])){ 
       $sql  .= $virgula." r29_media = $this->r29_media ";
       $virgula = ",";
       if(trim($this->r29_media) == null ){ 
         $this->erro_sql = " Campo Numero meses incidido na ficha nao Informado.";
         $this->erro_campo = "r29_media";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r29_calc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r29_calc"])){ 
       $sql  .= $virgula." r29_calc = $this->r29_calc ";
       $virgula = ",";
       if(trim($this->r29_calc) == null ){ 
         $this->erro_sql = " Campo Formula de Calculo nao Informado.";
         $this->erro_campo = "r29_calc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r29_tpp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r29_tpp"])){ 
       $sql  .= $virgula." r29_tpp = '$this->r29_tpp' ";
       $virgula = ",";
       if(trim($this->r29_tpp) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "r29_tpp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r29_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r29_instit"])){ 
       $sql  .= $virgula." r29_instit = $this->r29_instit ";
       $virgula = ",";
       if(trim($this->r29_instit) == null ){ 
         $this->erro_sql = " Campo codigo da instituicao nao Informado.";
         $this->erro_campo = "r29_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r29_anousu!=null){
       $sql .= " r29_anousu = $this->r29_anousu";
     }
     if($r29_mesusu!=null){
       $sql .= " and  r29_mesusu = $this->r29_mesusu";
     }
     if($r29_regist!=null){
       $sql .= " and  r29_regist = $this->r29_regist";
     }
     if($r29_rubric!=null){
       $sql .= " and  r29_rubric = '$this->r29_rubric'";
     }
     if($r29_tpp!=null){
       $sql .= " and  r29_tpp = '$this->r29_tpp'";
     }
     if(trim($where) != ""){
	     if(strpos("where",$sql) != ""){
	     	 $sql .= " and ";
	     }
	     $sql .= $where;
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r29_anousu,$this->r29_mesusu,$this->r29_regist,$this->r29_rubric,$this->r29_tpp));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4297,'$this->r29_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4298,'$this->r29_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4299,'$this->r29_regist','A')");
         $resac = db_query("insert into db_acountkey values($acount,4300,'$this->r29_rubric','A')");
         $resac = db_query("insert into db_acountkey values($acount,4306,'$this->r29_tpp','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r29_anousu"]) || $this->r29_anousu != "")
           $resac = db_query("insert into db_acount values($acount,577,4297,'".AddSlashes(pg_result($resaco,$conresaco,'r29_anousu'))."','$this->r29_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r29_mesusu"]) || $this->r29_mesusu != "")
           $resac = db_query("insert into db_acount values($acount,577,4298,'".AddSlashes(pg_result($resaco,$conresaco,'r29_mesusu'))."','$this->r29_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r29_regist"]) || $this->r29_regist != "")
           $resac = db_query("insert into db_acount values($acount,577,4299,'".AddSlashes(pg_result($resaco,$conresaco,'r29_regist'))."','$this->r29_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r29_rubric"]) || $this->r29_rubric != "")
           $resac = db_query("insert into db_acount values($acount,577,4300,'".AddSlashes(pg_result($resaco,$conresaco,'r29_rubric'))."','$this->r29_rubric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r29_valor"]) || $this->r29_valor != "")
           $resac = db_query("insert into db_acount values($acount,577,4301,'".AddSlashes(pg_result($resaco,$conresaco,'r29_valor'))."','$this->r29_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r29_quant"]) || $this->r29_quant != "")
           $resac = db_query("insert into db_acount values($acount,577,4302,'".AddSlashes(pg_result($resaco,$conresaco,'r29_quant'))."','$this->r29_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r29_lotac"]) || $this->r29_lotac != "")
           $resac = db_query("insert into db_acount values($acount,577,4303,'".AddSlashes(pg_result($resaco,$conresaco,'r29_lotac'))."','$this->r29_lotac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r29_media"]) || $this->r29_media != "")
           $resac = db_query("insert into db_acount values($acount,577,4304,'".AddSlashes(pg_result($resaco,$conresaco,'r29_media'))."','$this->r29_media',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r29_calc"]) || $this->r29_calc != "")
           $resac = db_query("insert into db_acount values($acount,577,4305,'".AddSlashes(pg_result($resaco,$conresaco,'r29_calc'))."','$this->r29_calc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r29_tpp"]) || $this->r29_tpp != "")
           $resac = db_query("insert into db_acount values($acount,577,4306,'".AddSlashes(pg_result($resaco,$conresaco,'r29_tpp'))."','$this->r29_tpp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r29_instit"]) || $this->r29_instit != "")
           $resac = db_query("insert into db_acount values($acount,577,7464,'".AddSlashes(pg_result($resaco,$conresaco,'r29_instit'))."','$this->r29_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ponto das Ferias nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r29_anousu."-".$this->r29_mesusu."-".$this->r29_regist."-".$this->r29_rubric."-".$this->r29_tpp;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ponto das Ferias nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r29_anousu."-".$this->r29_mesusu."-".$this->r29_regist."-".$this->r29_rubric."-".$this->r29_tpp;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r29_anousu."-".$this->r29_mesusu."-".$this->r29_regist."-".$this->r29_rubric."-".$this->r29_tpp;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r29_anousu=null,$r29_mesusu=null,$r29_regist=null,$r29_rubric=null,$r29_tpp=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r29_anousu,$r29_mesusu,$r29_regist,$r29_rubric,$r29_tpp));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4297,'$r29_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4298,'$r29_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4299,'$r29_regist','E')");
         $resac = db_query("insert into db_acountkey values($acount,4300,'$r29_rubric','E')");
         $resac = db_query("insert into db_acountkey values($acount,4306,'$r29_tpp','E')");
         $resac = db_query("insert into db_acount values($acount,577,4297,'','".AddSlashes(pg_result($resaco,$iresaco,'r29_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,577,4298,'','".AddSlashes(pg_result($resaco,$iresaco,'r29_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,577,4299,'','".AddSlashes(pg_result($resaco,$iresaco,'r29_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,577,4300,'','".AddSlashes(pg_result($resaco,$iresaco,'r29_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,577,4301,'','".AddSlashes(pg_result($resaco,$iresaco,'r29_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,577,4302,'','".AddSlashes(pg_result($resaco,$iresaco,'r29_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,577,4303,'','".AddSlashes(pg_result($resaco,$iresaco,'r29_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,577,4304,'','".AddSlashes(pg_result($resaco,$iresaco,'r29_media'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,577,4305,'','".AddSlashes(pg_result($resaco,$iresaco,'r29_calc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,577,4306,'','".AddSlashes(pg_result($resaco,$iresaco,'r29_tpp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,577,7464,'','".AddSlashes(pg_result($resaco,$iresaco,'r29_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pontofe
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r29_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r29_anousu = $r29_anousu ";
        }
        if($r29_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r29_mesusu = $r29_mesusu ";
        }
        if($r29_regist != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r29_regist = $r29_regist ";
        }
        if($r29_rubric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r29_rubric = '$r29_rubric' ";
        }
        if($r29_tpp != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r29_tpp = '$r29_tpp' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ponto das Ferias nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r29_anousu."-".$r29_mesusu."-".$r29_regist."-".$r29_rubric."-".$r29_tpp;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ponto das Ferias nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r29_anousu."-".$r29_mesusu."-".$r29_regist."-".$r29_rubric."-".$r29_tpp;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r29_anousu."-".$r29_mesusu."-".$r29_regist."-".$r29_rubric."-".$r29_tpp;
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
        $this->erro_sql   = "Record Vazio na Tabela:pontofe";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $r29_anousu=null,$r29_mesusu=null,$r29_regist=null,$r29_rubric=null,$r29_tpp=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pontofe ";
     $sql .= "      inner join db_config  on  db_config.codigo = pontofe.r29_instit";
     $sql .= "      inner join lotacao  on  lotacao.r13_anousu = pontofe.r29_anousu 
		                                   and  lotacao.r13_mesusu = pontofe.r29_mesusu 
																			 and  lotacao.r13_codigo = pontofe.r29_lotac
																			 and  lotacao.r13_instit = pontofe.r29_instit ";
     $sql .= "      inner join pessoal  on  pessoal.r01_anousu = pontofe.r29_anousu 
		                                   and  pessoal.r01_mesusu = pontofe.r29_mesusu 
																			 and  pessoal.r01_regist = pontofe.r29_regist
																			 and  pessoal.r01_instit = pontofe.r29_instit ";
     $sql .= "      inner join rubricas  on  rubricas.r06_anousu = pontofe.r29_anousu 
		                                    and  rubricas.r06_mesusu = pontofe.r29_mesusu 
																				and  rubricas.r06_codigo = pontofe.r29_rubric
																				and  rubricas.r06_instit = pontofe.r29_instit ";
     $sql .= "      inner join cgm  as d on   d.z01_numcgm = pessoal.r01_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = pessoal.r01_instit";
     $sql .= "      inner join funcao  as d on d.r37_anousu = pessoal.r01_anousu 
		                                       and d.r37_mesusu = pessoal.r01_mesusu 
																					 and   d.r37_funcao = pessoal.r01_funcao
																					 and   d.r37_instit = pessoal.r01_instit ";
     $sql .= "      inner join inssirf  as d on d.r33_anousu = pessoal.r01_anousu 
		                                        and d.r33_mesusu = pessoal.r01_mesusu 
																						and d.r33_codtab = pessoal.r01_tbprev
																						and d.r33_instit = pessoal.r01_instit ";
     $sql .= "      inner join cargo  as d on d.r65_anousu = pessoal.r01_anousu 
		                                      and d.r65_mesusu = pessoal.r01_mesusu 
																					and d.r65_cargo = pessoal.r01_cargo";
     $sql2 = "";
     if($dbwhere==""){
       if($r29_anousu!=null ){
         $sql2 .= " where pontofe.r29_anousu = $r29_anousu "; 
       } 
       if($r29_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofe.r29_mesusu = $r29_mesusu "; 
       } 
       if($r29_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofe.r29_regist = $r29_regist "; 
       } 
       if($r29_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofe.r29_rubric = '$r29_rubric' "; 
       } 
       if($r29_tpp!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofe.r29_tpp = '$r29_tpp' "; 
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
   function sql_query_file ( $r29_anousu=null,$r29_mesusu=null,$r29_regist=null,$r29_rubric=null,$r29_tpp=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pontofe ";
     $sql2 = "";
     if($dbwhere==""){
       if($r29_anousu!=null ){
         $sql2 .= " where pontofe.r29_anousu = $r29_anousu "; 
       } 
       if($r29_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofe.r29_mesusu = $r29_mesusu "; 
       } 
       if($r29_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofe.r29_regist = $r29_regist "; 
       } 
       if($r29_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofe.r29_rubric = '$r29_rubric' "; 
       } 
       if($r29_tpp!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofe.r29_tpp = '$r29_tpp' "; 
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
   function sql_query_seleciona ( $r29_anousu=null,$r29_mesusu=null,$r29_regist=null,$r29_rubric=null,$r29_tpp=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pontofe ";
     $sql .= "      inner join rhpessoal    on  rhpessoal.rh01_regist = pontofe.r29_regist";     
     $sql .= "      inner join rhpessoalmov on  rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist ";
     $sql .= "                             and  pontofe.r29_anousu          = rhpessoalmov.rh02_anousu ";
     $sql .= "                             and  pontofe.r29_mesusu          = rhpessoalmov.rh02_mesusu ";
     $sql .= "                             and  pontofe.r29_instit          = rhpessoalmov.rh02_instit ";
     $sql .= "      inner join rhfuncao     on  rhpessoalmov.rh02_funcao    = rhfuncao.rh37_funcao     ";
     $sql .= "                             and  rhpessoalmov.rh02_instit    = rhfuncao.rh37_instit     ";
     $sql .= "      inner join rhregime     on  rhpessoalmov.rh02_codreg    = rhregime.rh30_codreg     ";
     $sql .= "                             and  rhpessoalmov.rh02_instit    = rhregime.rh30_instit     ";
     $sql .= "      inner join rhrubricas   on  rhrubricas.rh27_rubric = pontofe.r29_rubric
		                                       and  rhrubricas.rh27_instit = pontofe.r29_instit ";
     $sql .= "      inner join rhlota       on  rhlota.r70_codigo::char(12) = pontofe.r29_lotac
		                                       and  rhlota.r70_instit = pontofe.r29_instit ";
     $sql .= "      inner join cgm          on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($r29_anousu!=null ){
         $sql2 .= " where pontofe.r29_anousu = $r29_anousu "; 
       } 
       if($r29_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofe.r29_mesusu = $r29_mesusu "; 
       } 
       if($r29_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofe.r29_regist = $r29_regist "; 
       } 
       if($r29_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofe.r29_rubric = '$r29_rubric' "; 
       } 
       if($r29_tpp!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofe.r29_tpp = '$r29_tpp' "; 
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