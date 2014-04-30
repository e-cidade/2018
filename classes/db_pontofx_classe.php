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
//CLASSE DA ENTIDADE pontofx
class cl_pontofx { 
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
   var $r90_anousu = 0; 
   var $r90_mesusu = 0; 
   var $r90_regist = 0; 
   var $r90_rubric = null; 
   var $r90_valor = 0; 
   var $r90_quant = 0; 
   var $r90_lotac = null; 
   var $r90_datlim = null; 
   var $r90_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r90_anousu = int4 = Ano do Exercicio 
                 r90_mesusu = int4 = Mes do Exercicio 
                 r90_regist = int4 = Matrícula 
                 r90_rubric = char(4) = Rubrica 
                 r90_valor = float8 = Valor 
                 r90_quant = float8 = Quantidade 
                 r90_lotac = char(4) = Lotação 
                 r90_datlim = char(7) = Ano/Mês 
                 r90_instit = int4 = codigo da instituicao 
                 ";
   //funcao construtor da classe 
   function cl_pontofx() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pontofx"); 
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
       $this->r90_anousu = ($this->r90_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r90_anousu"]:$this->r90_anousu);
       $this->r90_mesusu = ($this->r90_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r90_mesusu"]:$this->r90_mesusu);
       $this->r90_regist = ($this->r90_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r90_regist"]:$this->r90_regist);
       $this->r90_rubric = ($this->r90_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r90_rubric"]:$this->r90_rubric);
       $this->r90_valor = ($this->r90_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["r90_valor"]:$this->r90_valor);
       $this->r90_quant = ($this->r90_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["r90_quant"]:$this->r90_quant);
       $this->r90_lotac = ($this->r90_lotac == ""?@$GLOBALS["HTTP_POST_VARS"]["r90_lotac"]:$this->r90_lotac);
       $this->r90_datlim = ($this->r90_datlim == ""?@$GLOBALS["HTTP_POST_VARS"]["r90_datlim"]:$this->r90_datlim);
       $this->r90_instit = ($this->r90_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r90_instit"]:$this->r90_instit);
     }else{
       $this->r90_anousu = ($this->r90_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r90_anousu"]:$this->r90_anousu);
       $this->r90_mesusu = ($this->r90_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r90_mesusu"]:$this->r90_mesusu);
       $this->r90_regist = ($this->r90_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r90_regist"]:$this->r90_regist);
       $this->r90_rubric = ($this->r90_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r90_rubric"]:$this->r90_rubric);
     }
   }
   // funcao para inclusao
   function incluir ($r90_anousu,$r90_mesusu,$r90_regist,$r90_rubric){ 
      $this->atualizacampos();
     if($this->r90_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "r90_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r90_quant == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "r90_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r90_lotac == null ){ 
       $this->erro_sql = " Campo Lotação nao Informado.";
       $this->erro_campo = "r90_lotac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r90_instit == null ){ 
       $this->erro_sql = " Campo codigo da instituicao nao Informado.";
       $this->erro_campo = "r90_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r90_anousu = $r90_anousu; 
       $this->r90_mesusu = $r90_mesusu; 
       $this->r90_regist = $r90_regist; 
       $this->r90_rubric = $r90_rubric; 
     if(($this->r90_anousu == null) || ($this->r90_anousu == "") ){ 
       $this->erro_sql = " Campo r90_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r90_mesusu == null) || ($this->r90_mesusu == "") ){ 
       $this->erro_sql = " Campo r90_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r90_regist == null) || ($this->r90_regist == "") ){ 
       $this->erro_sql = " Campo r90_regist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r90_rubric == null) || ($this->r90_rubric == "") ){ 
       $this->erro_sql = " Campo r90_rubric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pontofx(
                                       r90_anousu 
                                      ,r90_mesusu 
                                      ,r90_regist 
                                      ,r90_rubric 
                                      ,r90_valor 
                                      ,r90_quant 
                                      ,r90_lotac 
                                      ,r90_datlim 
                                      ,r90_instit 
                       )
                values (
                                $this->r90_anousu 
                               ,$this->r90_mesusu 
                               ,$this->r90_regist 
                               ,'$this->r90_rubric' 
                               ,$this->r90_valor 
                               ,$this->r90_quant 
                               ,'$this->r90_lotac' 
                               ,'$this->r90_datlim' 
                               ,$this->r90_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ponto Fixo ($this->r90_anousu."-".$this->r90_mesusu."-".$this->r90_regist."-".$this->r90_rubric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ponto Fixo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ponto Fixo ($this->r90_anousu."-".$this->r90_mesusu."-".$this->r90_regist."-".$this->r90_rubric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r90_anousu."-".$this->r90_mesusu."-".$this->r90_regist."-".$this->r90_rubric;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r90_anousu,$this->r90_mesusu,$this->r90_regist,$this->r90_rubric));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4323,'$this->r90_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4324,'$this->r90_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4325,'$this->r90_regist','I')");
       $resac = db_query("insert into db_acountkey values($acount,4326,'$this->r90_rubric','I')");
       $resac = db_query("insert into db_acount values($acount,580,4323,'','".AddSlashes(pg_result($resaco,0,'r90_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,580,4324,'','".AddSlashes(pg_result($resaco,0,'r90_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,580,4325,'','".AddSlashes(pg_result($resaco,0,'r90_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,580,4326,'','".AddSlashes(pg_result($resaco,0,'r90_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,580,4327,'','".AddSlashes(pg_result($resaco,0,'r90_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,580,4328,'','".AddSlashes(pg_result($resaco,0,'r90_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,580,4329,'','".AddSlashes(pg_result($resaco,0,'r90_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,580,4330,'','".AddSlashes(pg_result($resaco,0,'r90_datlim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,580,7467,'','".AddSlashes(pg_result($resaco,0,'r90_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r90_anousu=null,$r90_mesusu=null,$r90_regist=null,$r90_rubric=null,$where="") { 
      $this->atualizacampos();
     $sql = " update pontofx set ";
     $virgula = "";
     if(trim($this->r90_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r90_anousu"])){ 
       $sql  .= $virgula." r90_anousu = $this->r90_anousu ";
       $virgula = ",";
       if(trim($this->r90_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "r90_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r90_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r90_mesusu"])){ 
       $sql  .= $virgula." r90_mesusu = $this->r90_mesusu ";
       $virgula = ",";
       if(trim($this->r90_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r90_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r90_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r90_regist"])){ 
       $sql  .= $virgula." r90_regist = $this->r90_regist ";
       $virgula = ",";
       if(trim($this->r90_regist) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "r90_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r90_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r90_rubric"])){ 
       $sql  .= $virgula." r90_rubric = '$this->r90_rubric' ";
       $virgula = ",";
       if(trim($this->r90_rubric) == null ){ 
         $this->erro_sql = " Campo Rubrica nao Informado.";
         $this->erro_campo = "r90_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r90_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r90_valor"])){ 
       $sql  .= $virgula." r90_valor = $this->r90_valor ";
       $virgula = ",";
       if(trim($this->r90_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "r90_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r90_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r90_quant"])){ 
       $sql  .= $virgula." r90_quant = $this->r90_quant ";
       $virgula = ",";
       if(trim($this->r90_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "r90_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r90_lotac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r90_lotac"])){ 
       $sql  .= $virgula." r90_lotac = '$this->r90_lotac' ";
       $virgula = ",";
       if(trim($this->r90_lotac) == null ){ 
         $this->erro_sql = " Campo Lotação nao Informado.";
         $this->erro_campo = "r90_lotac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r90_datlim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r90_datlim"])){ 
       $sql  .= $virgula." r90_datlim = '$this->r90_datlim' ";
       $virgula = ",";
     }
     if(trim($this->r90_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r90_instit"])){ 
       $sql  .= $virgula." r90_instit = $this->r90_instit ";
       $virgula = ",";
       if(trim($this->r90_instit) == null ){ 
         $this->erro_sql = " Campo codigo da instituicao nao Informado.";
         $this->erro_campo = "r90_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r90_anousu!=null){
       $sql .= " r90_anousu = $this->r90_anousu";
     }
     if($r90_mesusu!=null){
       $sql .= " and  r90_mesusu = $this->r90_mesusu";
     }
     if($r90_regist!=null){
       $sql .= " and  r90_regist = $this->r90_regist";
     }
     if($r90_rubric!=null){
       $sql .= " and  r90_rubric = '$this->r90_rubric'";
     }
     if(trim($where) != ""){
	     if(strpos("where",$sql) != ""){
	     	 $sql .= " and ";
	     }
	     $sql .= $where;
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r90_anousu,$this->r90_mesusu,$this->r90_regist,$this->r90_rubric));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4323,'$this->r90_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4324,'$this->r90_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4325,'$this->r90_regist','A')");
         $resac = db_query("insert into db_acountkey values($acount,4326,'$this->r90_rubric','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r90_anousu"]))
           $resac = db_query("insert into db_acount values($acount,580,4323,'".AddSlashes(pg_result($resaco,$conresaco,'r90_anousu'))."','$this->r90_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r90_mesusu"]))
           $resac = db_query("insert into db_acount values($acount,580,4324,'".AddSlashes(pg_result($resaco,$conresaco,'r90_mesusu'))."','$this->r90_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r90_regist"]))
           $resac = db_query("insert into db_acount values($acount,580,4325,'".AddSlashes(pg_result($resaco,$conresaco,'r90_regist'))."','$this->r90_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r90_rubric"]))
           $resac = db_query("insert into db_acount values($acount,580,4326,'".AddSlashes(pg_result($resaco,$conresaco,'r90_rubric'))."','$this->r90_rubric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r90_valor"]))
           $resac = db_query("insert into db_acount values($acount,580,4327,'".AddSlashes(pg_result($resaco,$conresaco,'r90_valor'))."','$this->r90_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r90_quant"]))
           $resac = db_query("insert into db_acount values($acount,580,4328,'".AddSlashes(pg_result($resaco,$conresaco,'r90_quant'))."','$this->r90_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r90_lotac"]))
           $resac = db_query("insert into db_acount values($acount,580,4329,'".AddSlashes(pg_result($resaco,$conresaco,'r90_lotac'))."','$this->r90_lotac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r90_datlim"]))
           $resac = db_query("insert into db_acount values($acount,580,4330,'".AddSlashes(pg_result($resaco,$conresaco,'r90_datlim'))."','$this->r90_datlim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r90_instit"]))
           $resac = db_query("insert into db_acount values($acount,580,7467,'".AddSlashes(pg_result($resaco,$conresaco,'r90_instit'))."','$this->r90_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ponto Fixo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r90_anousu."-".$this->r90_mesusu."-".$this->r90_regist."-".$this->r90_rubric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ponto Fixo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r90_anousu."-".$this->r90_mesusu."-".$this->r90_regist."-".$this->r90_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r90_anousu."-".$this->r90_mesusu."-".$this->r90_regist."-".$this->r90_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r90_anousu=null,$r90_mesusu=null,$r90_regist=null,$r90_rubric=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r90_anousu,$r90_mesusu,$r90_regist,$r90_rubric));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4323,'$r90_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4324,'$r90_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4325,'$r90_regist','E')");
         $resac = db_query("insert into db_acountkey values($acount,4326,'$r90_rubric','E')");
         $resac = db_query("insert into db_acount values($acount,580,4323,'','".AddSlashes(pg_result($resaco,$iresaco,'r90_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,580,4324,'','".AddSlashes(pg_result($resaco,$iresaco,'r90_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,580,4325,'','".AddSlashes(pg_result($resaco,$iresaco,'r90_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,580,4326,'','".AddSlashes(pg_result($resaco,$iresaco,'r90_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,580,4327,'','".AddSlashes(pg_result($resaco,$iresaco,'r90_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,580,4328,'','".AddSlashes(pg_result($resaco,$iresaco,'r90_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,580,4329,'','".AddSlashes(pg_result($resaco,$iresaco,'r90_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,580,4330,'','".AddSlashes(pg_result($resaco,$iresaco,'r90_datlim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,580,7467,'','".AddSlashes(pg_result($resaco,$iresaco,'r90_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pontofx
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r90_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r90_anousu = $r90_anousu ";
        }
        if($r90_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r90_mesusu = $r90_mesusu ";
        }
        if($r90_regist != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r90_regist = $r90_regist ";
        }
        if($r90_rubric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r90_rubric = '$r90_rubric' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ponto Fixo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r90_anousu."-".$r90_mesusu."-".$r90_regist."-".$r90_rubric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ponto Fixo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r90_anousu."-".$r90_mesusu."-".$r90_regist."-".$r90_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r90_anousu."-".$r90_mesusu."-".$r90_regist."-".$r90_rubric;
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
        $this->erro_sql   = "Record Vazio na Tabela:pontofx";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $r90_anousu=null,$r90_mesusu=null,$r90_regist=null,$r90_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pontofx ";
     $sql .= "      inner join db_config  on  db_config.codigo = pontofx.r90_instit";
     $sql .= "      inner join lotacao  on  lotacao.r13_anousu = pontofx.r90_anousu 
		                                   and  lotacao.r13_mesusu = pontofx.r90_mesusu 
																			 and  lotacao.r13_codigo = pontofx.r90_lotac
																			 and  lotacao.r13_instit = pontofx.r90_instit ";
     $sql .= "      inner join pessoal  on  pessoal.r01_anousu = pontofx.r90_anousu 
		                                   and  pessoal.r01_mesusu = pontofx.r90_mesusu 
																			 and  pessoal.r01_regist = pontofx.r90_regist
																			 and  pessoal.r01_instit = pontofx.r90_instit ";
     $sql .= "      inner join rubricas  on  rubricas.r06_anousu = pontofx.r90_anousu 
		                                    and  rubricas.r06_mesusu = pontofx.r90_mesusu 
																				and  rubricas.r06_codigo = pontofx.r90_rubric
																				and  rubricas.r06_instit = pontofx.r90_instit ";
     $sql .= "      inner join cgm  as d on   d.z01_numcgm = pessoal.r01_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = pessoal.r01_instit";
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
       if($r90_anousu!=null ){
         $sql2 .= " where pontofx.r90_anousu = $r90_anousu "; 
       } 
       if($r90_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofx.r90_mesusu = $r90_mesusu "; 
       } 
       if($r90_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofx.r90_regist = $r90_regist "; 
       } 
       if($r90_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofx.r90_rubric = '$r90_rubric' "; 
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
   function sql_query_file ( $r90_anousu=null,$r90_mesusu=null,$r90_regist=null,$r90_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pontofx ";
     $sql2 = "";
     if($dbwhere==""){
       if($r90_anousu!=null ){
         $sql2 .= " where pontofx.r90_anousu = $r90_anousu "; 
       } 
       if($r90_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofx.r90_mesusu = $r90_mesusu "; 
       } 
       if($r90_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofx.r90_regist = $r90_regist "; 
       } 
       if($r90_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofx.r90_rubric = '$r90_rubric' "; 
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
   function sql_query_rescis ( $r90_anousu=null,$r90_mesusu=null,$r90_regist=null,$r90_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pontofx ";
     $sql .= "      inner join rhpessoalmov  on  rhpessoalmov.rh02_anousu = pontofx.r90_anousu
                                            and  rhpessoalmov.rh02_mesusu = pontofx.r90_mesusu
					                                  and  rhpessoalmov.rh02_regist = pontofx.r90_regist
																						and  rhpessoalmov.rh02_instit = pontofx.r90_instit ";
     $sql .= "     left   join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes ";
     $sql2 = "";
     if($dbwhere==""){
       if($r90_anousu!=null ){
         $sql2 .= " where pontofx.r90_anousu = $r90_anousu "; 
       } 
       if($r90_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofx.r90_mesusu = $r90_mesusu "; 
       } 
       if($r90_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofx.r90_regist = $r90_regist "; 
       } 
       if($r90_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofx.r90_rubric = '$r90_rubric' "; 
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
   function sql_query_rubrica ( $r90_anousu=null,$r90_mesusu=null,$r90_regist=null,$r90_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pontofx ";
     $sql .= "      inner join rhrubricas  on rh27_rubric = r90_rubric
                                          and rh27_instit = r90_instit ";
     $sql2 = "";
     if($dbwhere==""){
       if($r90_anousu!=null ){
         $sql2 .= " where pontofx.r90_anousu = $r90_anousu "; 
       } 
       if($r90_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofx.r90_mesusu = $r90_mesusu "; 
       } 
       if($r90_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofx.r90_regist = $r90_regist "; 
       } 
       if($r90_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofx.r90_rubric = '$r90_rubric' "; 
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
   function sql_query_seleciona ( $r90_anousu=null,$r90_mesusu=null,$r90_regist=null,$r90_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pontofx ";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = pontofx.r90_regist";
     $sql .= "      inner join rhpessoalmov on  rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist ";
     $sql .= "                             and  pontofx.r90_anousu          = rhpessoalmov.rh02_anousu ";
     $sql .= "                             and  pontofx.r90_mesusu          = rhpessoalmov.rh02_mesusu ";
     $sql .= "                             and  pontofx.r90_instit          = rhpessoalmov.rh02_instit ";
     $sql .= "      inner join rhfuncao     on  rhpessoalmov.rh02_funcao    = rhfuncao.rh37_funcao     ";
     $sql .= "                             and  rhpessoalmov.rh02_instit    = rhfuncao.rh37_instit     ";
     $sql .= "      inner join rhregime     on  rhpessoalmov.rh02_codreg    = rhregime.rh30_codreg     ";
     $sql .= "                             and  rhpessoalmov.rh02_instit    = rhregime.rh30_instit     ";
     $sql .= "      inner join rhrubricas  on  rhrubricas.rh27_rubric = pontofx.r90_rubric
		                                      and  rhrubricas.rh27_instit = pontofx.r90_instit ";
     $sql .= "      inner join rhlota  on  rhlota.r70_codigo::char(12) = pontofx.r90_lotac
		                                  and  rhlota.r70_instit = pontofx.r90_instit ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($r90_anousu!=null ){
         $sql2 .= " where pontofx.r90_anousu = $r90_anousu "; 
       } 
       if($r90_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofx.r90_mesusu = $r90_mesusu "; 
       } 
       if($r90_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofx.r90_regist = $r90_regist "; 
       } 
       if($r90_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofx.r90_rubric = '$r90_rubric' "; 
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