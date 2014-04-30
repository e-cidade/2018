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
//CLASSE DA ENTIDADE pontofr
class cl_pontofr { 
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
   var $r19_anousu = 0; 
   var $r19_mesusu = 0; 
   var $r19_regist = 0; 
   var $r19_rubric = null; 
   var $r19_valor = 0; 
   var $r19_quant = 0; 
   var $r19_lotac = null; 
   var $r19_tpp = null; 
   var $r19_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r19_anousu = int4 = Ano do Exercicio 
                 r19_mesusu = int4 = Mes do Exercicio 
                 r19_regist = int4 = Matrícula 
                 r19_rubric = char(     4) = Codigo da Rubrica 
                 r19_valor = float8 = Valor do Ponto 
                 r19_quant = float8 = Quantidade 
                 r19_lotac = char(4) = Lotação 
                 r19_tpp = char(1) = (P)rop, (V)enc e (S)aldo 
                 r19_instit = int4 = codigo da instituicao 
                 ";
   //funcao construtor da classe 
   function cl_pontofr() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pontofr"); 
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
       $this->r19_anousu = ($this->r19_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r19_anousu"]:$this->r19_anousu);
       $this->r19_mesusu = ($this->r19_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r19_mesusu"]:$this->r19_mesusu);
       $this->r19_regist = ($this->r19_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r19_regist"]:$this->r19_regist);
       $this->r19_rubric = ($this->r19_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r19_rubric"]:$this->r19_rubric);
       $this->r19_valor = ($this->r19_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["r19_valor"]:$this->r19_valor);
       $this->r19_quant = ($this->r19_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["r19_quant"]:$this->r19_quant);
       $this->r19_lotac = ($this->r19_lotac == ""?@$GLOBALS["HTTP_POST_VARS"]["r19_lotac"]:$this->r19_lotac);
       $this->r19_tpp = ($this->r19_tpp == ""?@$GLOBALS["HTTP_POST_VARS"]["r19_tpp"]:$this->r19_tpp);
       $this->r19_instit = ($this->r19_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r19_instit"]:$this->r19_instit);
     }else{
       $this->r19_anousu = ($this->r19_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r19_anousu"]:$this->r19_anousu);
       $this->r19_mesusu = ($this->r19_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r19_mesusu"]:$this->r19_mesusu);
       $this->r19_regist = ($this->r19_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r19_regist"]:$this->r19_regist);
       $this->r19_rubric = ($this->r19_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r19_rubric"]:$this->r19_rubric);
       $this->r19_tpp = ($this->r19_tpp == ""?@$GLOBALS["HTTP_POST_VARS"]["r19_tpp"]:$this->r19_tpp);
     }
   }
   // funcao para inclusao
   function incluir ($r19_anousu,$r19_mesusu,$r19_regist,$r19_rubric,$r19_tpp){ 
      $this->atualizacampos();
     if($this->r19_valor == null ){ 
       $this->erro_sql = " Campo Valor do Ponto nao Informado.";
       $this->erro_campo = "r19_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r19_quant == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "r19_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r19_lotac == null ){ 
       $this->erro_sql = " Campo Lotação nao Informado.";
       $this->erro_campo = "r19_lotac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r19_instit == null ){ 
       $this->erro_sql = " Campo codigo da instituicao nao Informado.";
       $this->erro_campo = "r19_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r19_anousu = $r19_anousu; 
       $this->r19_mesusu = $r19_mesusu; 
       $this->r19_regist = $r19_regist; 
       $this->r19_rubric = $r19_rubric; 
       $this->r19_tpp = $r19_tpp; 
     if(($this->r19_anousu == null) || ($this->r19_anousu == "") ){ 
       $this->erro_sql = " Campo r19_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r19_mesusu == null) || ($this->r19_mesusu == "") ){ 
       $this->erro_sql = " Campo r19_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r19_regist == null) || ($this->r19_regist == "") ){ 
       $this->erro_sql = " Campo r19_regist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r19_rubric == null) || ($this->r19_rubric == "") ){ 
       $this->erro_sql = " Campo r19_rubric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r19_tpp == null) || ($this->r19_tpp == "") ){ 
       $this->erro_sql = " Campo r19_tpp nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pontofr(
                                       r19_anousu 
                                      ,r19_mesusu 
                                      ,r19_regist 
                                      ,r19_rubric 
                                      ,r19_valor 
                                      ,r19_quant 
                                      ,r19_lotac 
                                      ,r19_tpp 
                                      ,r19_instit 
                       )
                values (
                                $this->r19_anousu 
                               ,$this->r19_mesusu 
                               ,$this->r19_regist 
                               ,'$this->r19_rubric' 
                               ,$this->r19_valor 
                               ,$this->r19_quant 
                               ,'$this->r19_lotac' 
                               ,'$this->r19_tpp' 
                               ,$this->r19_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ponto de Rescisao ($this->r19_anousu."-".$this->r19_mesusu."-".$this->r19_regist."-".$this->r19_rubric."-".$this->r19_tpp) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ponto de Rescisao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ponto de Rescisao ($this->r19_anousu."-".$this->r19_mesusu."-".$this->r19_regist."-".$this->r19_rubric."-".$this->r19_tpp) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r19_anousu."-".$this->r19_mesusu."-".$this->r19_regist."-".$this->r19_rubric."-".$this->r19_tpp;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r19_anousu,$this->r19_mesusu,$this->r19_regist,$this->r19_rubric,$this->r19_tpp));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4307,'$this->r19_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4308,'$this->r19_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4309,'$this->r19_regist','I')");
       $resac = db_query("insert into db_acountkey values($acount,4310,'$this->r19_rubric','I')");
       $resac = db_query("insert into db_acountkey values($acount,4314,'$this->r19_tpp','I')");
       $resac = db_query("insert into db_acount values($acount,578,4307,'','".AddSlashes(pg_result($resaco,0,'r19_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,578,4308,'','".AddSlashes(pg_result($resaco,0,'r19_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,578,4309,'','".AddSlashes(pg_result($resaco,0,'r19_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,578,4310,'','".AddSlashes(pg_result($resaco,0,'r19_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,578,4311,'','".AddSlashes(pg_result($resaco,0,'r19_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,578,4312,'','".AddSlashes(pg_result($resaco,0,'r19_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,578,4313,'','".AddSlashes(pg_result($resaco,0,'r19_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,578,4314,'','".AddSlashes(pg_result($resaco,0,'r19_tpp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,578,7465,'','".AddSlashes(pg_result($resaco,0,'r19_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r19_anousu=null,$r19_mesusu=null,$r19_regist=null,$r19_rubric=null,$r19_tpp=null, $where="") { 
      $this->atualizacampos();
     $sql = " update pontofr set ";
     $virgula = "";
     if(trim($this->r19_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r19_anousu"])){ 
       $sql  .= $virgula." r19_anousu = $this->r19_anousu ";
       $virgula = ",";
       if(trim($this->r19_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "r19_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r19_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r19_mesusu"])){ 
       $sql  .= $virgula." r19_mesusu = $this->r19_mesusu ";
       $virgula = ",";
       if(trim($this->r19_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r19_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r19_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r19_regist"])){ 
       $sql  .= $virgula." r19_regist = $this->r19_regist ";
       $virgula = ",";
       if(trim($this->r19_regist) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "r19_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r19_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r19_rubric"])){ 
       $sql  .= $virgula." r19_rubric = '$this->r19_rubric' ";
       $virgula = ",";
       if(trim($this->r19_rubric) == null ){ 
         $this->erro_sql = " Campo Codigo da Rubrica nao Informado.";
         $this->erro_campo = "r19_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r19_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r19_valor"])){ 
       $sql  .= $virgula." r19_valor = $this->r19_valor ";
       $virgula = ",";
       if(trim($this->r19_valor) == null ){ 
         $this->erro_sql = " Campo Valor do Ponto nao Informado.";
         $this->erro_campo = "r19_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r19_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r19_quant"])){ 
       $sql  .= $virgula." r19_quant = $this->r19_quant ";
       $virgula = ",";
       if(trim($this->r19_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "r19_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r19_lotac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r19_lotac"])){ 
       $sql  .= $virgula." r19_lotac = '$this->r19_lotac' ";
       $virgula = ",";
       if(trim($this->r19_lotac) == null ){ 
         $this->erro_sql = " Campo Lotação nao Informado.";
         $this->erro_campo = "r19_lotac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r19_tpp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r19_tpp"])){ 
       $sql  .= $virgula." r19_tpp = '$this->r19_tpp' ";
       $virgula = ",";
       if(trim($this->r19_tpp) == null ){ 
         $this->erro_sql = " Campo (P)rop, (V)enc e (S)aldo nao Informado.";
         $this->erro_campo = "r19_tpp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r19_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r19_instit"])){ 
       $sql  .= $virgula." r19_instit = $this->r19_instit ";
       $virgula = ",";
       if(trim($this->r19_instit) == null ){ 
         $this->erro_sql = " Campo codigo da instituicao nao Informado.";
         $this->erro_campo = "r19_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r19_anousu!=null){
       $sql .= " r19_anousu = $this->r19_anousu";
     }
     if($r19_mesusu!=null){
       $sql .= " and  r19_mesusu = $this->r19_mesusu";
     }
     if($r19_regist!=null){
       $sql .= " and  r19_regist = $this->r19_regist";
     }
     if($r19_rubric!=null){
       $sql .= " and  r19_rubric = '$this->r19_rubric'";
     }
     if($r19_tpp!=null){
       $sql .= " and  r19_tpp = '$this->r19_tpp'";
     }
     if(trim($where) != ""){
	     if(strpos("where",$sql) != ""){
	     	 $sql .= " and ";
	     }
	     $sql .= $where;
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r19_anousu,$this->r19_mesusu,$this->r19_regist,$this->r19_rubric,$this->r19_tpp));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4307,'$this->r19_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4308,'$this->r19_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4309,'$this->r19_regist','A')");
         $resac = db_query("insert into db_acountkey values($acount,4310,'$this->r19_rubric','A')");
         $resac = db_query("insert into db_acountkey values($acount,4314,'$this->r19_tpp','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r19_anousu"]) || $this->r19_anousu != "")
           $resac = db_query("insert into db_acount values($acount,578,4307,'".AddSlashes(pg_result($resaco,$conresaco,'r19_anousu'))."','$this->r19_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r19_mesusu"]) || $this->r19_mesusu != "")
           $resac = db_query("insert into db_acount values($acount,578,4308,'".AddSlashes(pg_result($resaco,$conresaco,'r19_mesusu'))."','$this->r19_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r19_regist"]) || $this->r19_regist != "")
           $resac = db_query("insert into db_acount values($acount,578,4309,'".AddSlashes(pg_result($resaco,$conresaco,'r19_regist'))."','$this->r19_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r19_rubric"]) || $this->r19_rubric != "")
           $resac = db_query("insert into db_acount values($acount,578,4310,'".AddSlashes(pg_result($resaco,$conresaco,'r19_rubric'))."','$this->r19_rubric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r19_valor"]) || $this->r19_valor != "")
           $resac = db_query("insert into db_acount values($acount,578,4311,'".AddSlashes(pg_result($resaco,$conresaco,'r19_valor'))."','$this->r19_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r19_quant"]) || $this->r19_quant != "")
           $resac = db_query("insert into db_acount values($acount,578,4312,'".AddSlashes(pg_result($resaco,$conresaco,'r19_quant'))."','$this->r19_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r19_lotac"]) || $this->r19_lotac != "")
           $resac = db_query("insert into db_acount values($acount,578,4313,'".AddSlashes(pg_result($resaco,$conresaco,'r19_lotac'))."','$this->r19_lotac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r19_tpp"]) || $this->r19_tpp != "")
           $resac = db_query("insert into db_acount values($acount,578,4314,'".AddSlashes(pg_result($resaco,$conresaco,'r19_tpp'))."','$this->r19_tpp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r19_instit"]) || $this->r19_instit != "")
           $resac = db_query("insert into db_acount values($acount,578,7465,'".AddSlashes(pg_result($resaco,$conresaco,'r19_instit'))."','$this->r19_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ponto de Rescisao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r19_anousu."-".$this->r19_mesusu."-".$this->r19_regist."-".$this->r19_rubric."-".$this->r19_tpp;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ponto de Rescisao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r19_anousu."-".$this->r19_mesusu."-".$this->r19_regist."-".$this->r19_rubric."-".$this->r19_tpp;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r19_anousu."-".$this->r19_mesusu."-".$this->r19_regist."-".$this->r19_rubric."-".$this->r19_tpp;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r19_anousu=null,$r19_mesusu=null,$r19_regist=null,$r19_rubric=null,$r19_tpp=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r19_anousu,$r19_mesusu,$r19_regist,$r19_rubric,$r19_tpp));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4307,'$r19_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4308,'$r19_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4309,'$r19_regist','E')");
         $resac = db_query("insert into db_acountkey values($acount,4310,'$r19_rubric','E')");
         $resac = db_query("insert into db_acountkey values($acount,4314,'$r19_tpp','E')");
         $resac = db_query("insert into db_acount values($acount,578,4307,'','".AddSlashes(pg_result($resaco,$iresaco,'r19_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,578,4308,'','".AddSlashes(pg_result($resaco,$iresaco,'r19_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,578,4309,'','".AddSlashes(pg_result($resaco,$iresaco,'r19_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,578,4310,'','".AddSlashes(pg_result($resaco,$iresaco,'r19_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,578,4311,'','".AddSlashes(pg_result($resaco,$iresaco,'r19_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,578,4312,'','".AddSlashes(pg_result($resaco,$iresaco,'r19_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,578,4313,'','".AddSlashes(pg_result($resaco,$iresaco,'r19_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,578,4314,'','".AddSlashes(pg_result($resaco,$iresaco,'r19_tpp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,578,7465,'','".AddSlashes(pg_result($resaco,$iresaco,'r19_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pontofr
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r19_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r19_anousu = $r19_anousu ";
        }
        if($r19_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r19_mesusu = $r19_mesusu ";
        }
        if($r19_regist != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r19_regist = $r19_regist ";
        }
        if($r19_rubric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r19_rubric = '$r19_rubric' ";
        }
        if($r19_tpp != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r19_tpp = '$r19_tpp' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ponto de Rescisao nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r19_anousu."-".$r19_mesusu."-".$r19_regist."-".$r19_rubric."-".$r19_tpp;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ponto de Rescisao nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r19_anousu."-".$r19_mesusu."-".$r19_regist."-".$r19_rubric."-".$r19_tpp;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r19_anousu."-".$r19_mesusu."-".$r19_regist."-".$r19_rubric."-".$r19_tpp;
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
        $this->erro_sql   = "Record Vazio na Tabela:pontofr";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $r19_anousu=null,$r19_mesusu=null,$r19_regist=null,$r19_rubric=null,$r19_tpp=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pontofr ";
     $sql .= "      inner join db_config  on  db_config.codigo = pontofr.r19_instit";
     $sql .= "      inner join lotacao  on  lotacao.r13_anousu = pontofr.r19_anousu 
		                                   and  lotacao.r13_mesusu = pontofr.r19_mesusu 
																			 and  lotacao.r13_codigo = pontofr.r19_lotac
																			 and  lotacao.r13_instit = pontofr.r19_instit ";
     $sql .= "      inner join pessoal  on  pessoal.r01_anousu = pontofr.r19_anousu 
		                                   and  pessoal.r01_mesusu = pontofr.r19_mesusu 
																			 and  pessoal.r01_regist = pontofr.r19_regist
																			 and  pessoal.r01_instit = pontofr.r19_instit ";
     $sql .= "      inner join rubricas  on rubricas.r06_anousu = pontofr.r19_anousu 
		                                    and rubricas.r06_mesusu = pontofr.r19_mesusu 
																				and  rubricas.r06_codigo = pontofr.r19_rubric
																				and  rubricas.r06_instit = pontofr.r19_instit ";
     $sql .= "      inner join cgm  as d on   d.z01_numcgm = pessoal.r01_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = pessoal.r01_instit";
     $sql .= "      inner join funcao  as d on d.r37_anousu = pessoal.r01_anousu 
		                                       and d.r37_mesusu = pessoal.r01_mesusu 
																					 and d.r37_funcao = pessoal.r01_funcao
																					 and d.r37_instit = pessoal.r01_instit ";
     $sql .= "      inner join inssirf  as d on d.r33_anousu = pessoal.r01_anousu 
		                                        and d.r33_mesusu = pessoal.r01_mesusu 
																						and d.r33_codtab = pessoal.r01_tbprev
																						and d.r33_instit = pessoal.r01_instit";
     $sql .= "      inner join cargo  as d on d.r65_anousu = pessoal.r01_anousu 
		                                      and d.r65_mesusu = pessoal.r01_mesusu 
																					and d.r65_cargo = pessoal.r01_cargo";
     $sql2 = "";
     if($dbwhere==""){
       if($r19_anousu!=null ){
         $sql2 .= " where pontofr.r19_anousu = $r19_anousu "; 
       } 
       if($r19_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofr.r19_mesusu = $r19_mesusu "; 
       } 
       if($r19_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofr.r19_regist = $r19_regist "; 
       } 
       if($r19_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofr.r19_rubric = '$r19_rubric' "; 
       } 
       if($r19_tpp!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofr.r19_tpp = '$r19_tpp' "; 
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
   function sql_query_file ( $r19_anousu=null,$r19_mesusu=null,$r19_regist=null,$r19_rubric=null,$r19_tpp=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pontofr ";
     $sql2 = "";
     if($dbwhere==""){
       if($r19_anousu!=null ){
         $sql2 .= " where pontofr.r19_anousu = $r19_anousu "; 
       } 
       if($r19_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofr.r19_mesusu = $r19_mesusu "; 
       } 
       if($r19_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofr.r19_regist = $r19_regist "; 
       } 
       if($r19_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofr.r19_rubric = '$r19_rubric' "; 
       } 
       if($r19_tpp!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofr.r19_tpp = '$r19_tpp' "; 
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
   function sql_query_seleciona ( $r19_anousu=null,$r19_mesusu=null,$r19_regist=null,$r19_rubric=null,$r19_tpp=null,$campos="*",$ordem=null,$dbwhere=""){
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

     $sql .= " from pontofr ";
     $sql .= "      inner join rhpessoal    on  rhpessoal.rh01_regist       = pontofr.r19_regist       ";
     $sql .= "      inner join rhpessoalmov on  rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist ";
     $sql .= "                             and  pontofr.r19_anousu          = rhpessoalmov.rh02_anousu ";
     $sql .= "                             and  pontofr.r19_mesusu          = rhpessoalmov.rh02_mesusu ";
     $sql .= "                             and  pontofr.r19_instit          = rhpessoalmov.rh02_instit ";
     $sql .= "      inner join rhfuncao     on  rhpessoalmov.rh02_funcao    = rhfuncao.rh37_funcao     ";
     $sql .= "                             and  rhpessoalmov.rh02_instit    = rhfuncao.rh37_instit     ";
     $sql .= "      inner join rhregime     on  rhpessoalmov.rh02_codreg    = rhregime.rh30_codreg     ";
     $sql .= "                             and  rhpessoalmov.rh02_instit    = rhregime.rh30_instit     ";
     $sql .= "      inner join rhrubricas   on  rhrubricas.rh27_rubric      = pontofr.r19_rubric       ";
		 $sql .= "                             and  rhrubricas.rh27_instit      = pontofr.r19_instit       ";
     $sql .= "      inner join rhlota       on  rhlota.r70_codigo::char(12) = pontofr.r19_lotac        ";
		 $sql .= "                             and  rhlota.r70_instit           = pontofr.r19_instit       ";
     $sql .= "      inner join cgm          on  cgm.z01_numcgm              = rhpessoal.rh01_numcgm    ";

     $sql2 = "";
     if($dbwhere==""){     	
       if($r19_anousu!=null ){
         $sql2 .= " where pontofr.r19_anousu = $r19_anousu "; 
       } 
       if($r19_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofr.r19_mesusu = $r19_mesusu "; 
       } 
       if($r19_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " pontofr.r19_regist = $r19_regist ";
       }
       if($r19_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " pontofr.r19_rubric = '$r19_rubric' ";
       }
       if($r19_tpp!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " pontofr.r19_tpp = '$r19_tpp' ";
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