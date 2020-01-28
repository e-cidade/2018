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
//CLASSE DA ENTIDADE isscalcant
class cl_isscalcant { 
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
   var $q15_anousu = 0; 
   var $q15_inscr = 0; 
   var $q15_cadcal = 0; 
   var $q15_recei = 0; 
   var $q15_numpre = 0; 
   var $q15_valor = 0; 
   var $q15_manual = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q15_anousu = int4 = ano 
                 q15_inscr = int4 = inscricao 
                 q15_cadcal = int4 = codigo do calculo 
                 q15_recei = int4 = receita 
                 q15_numpre = int4 = numpre 
                 q15_valor = float8 = valor 
                 q15_manual = text = Log do calculo 
                 ";
   //funcao construtor da classe 
   function cl_isscalcant() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("isscalcant"); 
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
       $this->q15_anousu = ($this->q15_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["q15_anousu"]:$this->q15_anousu);
       $this->q15_inscr = ($this->q15_inscr == ""?@$GLOBALS["HTTP_POST_VARS"]["q15_inscr"]:$this->q15_inscr);
       $this->q15_cadcal = ($this->q15_cadcal == ""?@$GLOBALS["HTTP_POST_VARS"]["q15_cadcal"]:$this->q15_cadcal);
       $this->q15_recei = ($this->q15_recei == ""?@$GLOBALS["HTTP_POST_VARS"]["q15_recei"]:$this->q15_recei);
       $this->q15_numpre = ($this->q15_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["q15_numpre"]:$this->q15_numpre);
       $this->q15_valor = ($this->q15_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["q15_valor"]:$this->q15_valor);
       $this->q15_manual = ($this->q15_manual == ""?@$GLOBALS["HTTP_POST_VARS"]["q15_manual"]:$this->q15_manual);
     }else{
       $this->q15_anousu = ($this->q15_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["q15_anousu"]:$this->q15_anousu);
       $this->q15_inscr = ($this->q15_inscr == ""?@$GLOBALS["HTTP_POST_VARS"]["q15_inscr"]:$this->q15_inscr);
       $this->q15_cadcal = ($this->q15_cadcal == ""?@$GLOBALS["HTTP_POST_VARS"]["q15_cadcal"]:$this->q15_cadcal);
       $this->q15_recei = ($this->q15_recei == ""?@$GLOBALS["HTTP_POST_VARS"]["q15_recei"]:$this->q15_recei);
       $this->q15_numpre = ($this->q15_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["q15_numpre"]:$this->q15_numpre);
     }
   }
   // funcao para inclusao
   function incluir ($q15_anousu,$q15_inscr,$q15_cadcal,$q15_recei,$q15_numpre){ 
      $this->atualizacampos();
     if($this->q15_valor == null ){ 
       $this->erro_sql = " Campo valor nao Informado.";
       $this->erro_campo = "q15_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q15_manual == null ){ 
       $this->erro_sql = " Campo Log do calculo nao Informado.";
       $this->erro_campo = "q15_manual";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->q15_anousu = $q15_anousu; 
       $this->q15_inscr = $q15_inscr; 
       $this->q15_cadcal = $q15_cadcal; 
       $this->q15_recei = $q15_recei; 
       $this->q15_numpre = $q15_numpre; 
     if(($this->q15_anousu == null) || ($this->q15_anousu == "") ){ 
       $this->erro_sql = " Campo q15_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->q15_inscr == null) || ($this->q15_inscr == "") ){ 
       $this->erro_sql = " Campo q15_inscr nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->q15_cadcal == null) || ($this->q15_cadcal == "") ){ 
       $this->erro_sql = " Campo q15_cadcal nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->q15_recei == null) || ($this->q15_recei == "") ){ 
       $this->erro_sql = " Campo q15_recei nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->q15_numpre == null) || ($this->q15_numpre == "") ){ 
       $this->erro_sql = " Campo q15_numpre nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into isscalcant(
                                       q15_anousu 
                                      ,q15_inscr 
                                      ,q15_cadcal 
                                      ,q15_recei 
                                      ,q15_numpre 
                                      ,q15_valor 
                                      ,q15_manual 
                       )
                values (
                                $this->q15_anousu 
                               ,$this->q15_inscr 
                               ,$this->q15_cadcal 
                               ,$this->q15_recei 
                               ,$this->q15_numpre 
                               ,$this->q15_valor 
                               ,'$this->q15_manual' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->q15_anousu."-".$this->q15_inscr."-".$this->q15_cadcal."-".$this->q15_recei."-".$this->q15_numpre) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->q15_anousu."-".$this->q15_inscr."-".$this->q15_cadcal."-".$this->q15_recei."-".$this->q15_numpre) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q15_anousu."-".$this->q15_inscr."-".$this->q15_cadcal."-".$this->q15_recei."-".$this->q15_numpre;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q15_anousu,$this->q15_inscr,$this->q15_cadcal,$this->q15_recei,$this->q15_numpre));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,316,'$this->q15_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,323,'$this->q15_inscr','I')");
       $resac = db_query("insert into db_acountkey values($acount,318,'$this->q15_cadcal','I')");
       $resac = db_query("insert into db_acountkey values($acount,319,'$this->q15_recei','I')");
       $resac = db_query("insert into db_acountkey values($acount,320,'$this->q15_numpre','I')");
       $resac = db_query("insert into db_acount values($acount,62,316,'','".AddSlashes(pg_result($resaco,0,'q15_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,62,323,'','".AddSlashes(pg_result($resaco,0,'q15_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,62,318,'','".AddSlashes(pg_result($resaco,0,'q15_cadcal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,62,319,'','".AddSlashes(pg_result($resaco,0,'q15_recei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,62,320,'','".AddSlashes(pg_result($resaco,0,'q15_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,62,321,'','".AddSlashes(pg_result($resaco,0,'q15_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,62,6841,'','".AddSlashes(pg_result($resaco,0,'q15_manual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q15_anousu=null,$q15_inscr=null,$q15_cadcal=null,$q15_recei=null,$q15_numpre=null) { 
      $this->atualizacampos();
     $sql = " update isscalcant set ";
     $virgula = "";
     if(trim($this->q15_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q15_anousu"])){ 
       $sql  .= $virgula." q15_anousu = $this->q15_anousu ";
       $virgula = ",";
       if(trim($this->q15_anousu) == null ){ 
         $this->erro_sql = " Campo ano nao Informado.";
         $this->erro_campo = "q15_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q15_inscr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q15_inscr"])){ 
       $sql  .= $virgula." q15_inscr = $this->q15_inscr ";
       $virgula = ",";
       if(trim($this->q15_inscr) == null ){ 
         $this->erro_sql = " Campo inscricao nao Informado.";
         $this->erro_campo = "q15_inscr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q15_cadcal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q15_cadcal"])){ 
       $sql  .= $virgula." q15_cadcal = $this->q15_cadcal ";
       $virgula = ",";
       if(trim($this->q15_cadcal) == null ){ 
         $this->erro_sql = " Campo codigo do calculo nao Informado.";
         $this->erro_campo = "q15_cadcal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q15_recei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q15_recei"])){ 
       $sql  .= $virgula." q15_recei = $this->q15_recei ";
       $virgula = ",";
       if(trim($this->q15_recei) == null ){ 
         $this->erro_sql = " Campo receita nao Informado.";
         $this->erro_campo = "q15_recei";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q15_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q15_numpre"])){ 
       $sql  .= $virgula." q15_numpre = $this->q15_numpre ";
       $virgula = ",";
       if(trim($this->q15_numpre) == null ){ 
         $this->erro_sql = " Campo numpre nao Informado.";
         $this->erro_campo = "q15_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q15_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q15_valor"])){ 
       $sql  .= $virgula." q15_valor = $this->q15_valor ";
       $virgula = ",";
       if(trim($this->q15_valor) == null ){ 
         $this->erro_sql = " Campo valor nao Informado.";
         $this->erro_campo = "q15_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q15_manual)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q15_manual"])){ 
       $sql  .= $virgula." q15_manual = '$this->q15_manual' ";
       $virgula = ",";
       if(trim($this->q15_manual) == null ){ 
         $this->erro_sql = " Campo Log do calculo nao Informado.";
         $this->erro_campo = "q15_manual";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q15_anousu!=null){
       $sql .= " q15_anousu = $this->q15_anousu";
     }
     if($q15_inscr!=null){
       $sql .= " and  q15_inscr = $this->q15_inscr";
     }
     if($q15_cadcal!=null){
       $sql .= " and  q15_cadcal = $this->q15_cadcal";
     }
     if($q15_recei!=null){
       $sql .= " and  q15_recei = $this->q15_recei";
     }
     if($q15_numpre!=null){
       $sql .= " and  q15_numpre = $this->q15_numpre";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q15_anousu,$this->q15_inscr,$this->q15_cadcal,$this->q15_recei,$this->q15_numpre));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,316,'$this->q15_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,323,'$this->q15_inscr','A')");
         $resac = db_query("insert into db_acountkey values($acount,318,'$this->q15_cadcal','A')");
         $resac = db_query("insert into db_acountkey values($acount,319,'$this->q15_recei','A')");
         $resac = db_query("insert into db_acountkey values($acount,320,'$this->q15_numpre','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q15_anousu"]))
           $resac = db_query("insert into db_acount values($acount,62,316,'".AddSlashes(pg_result($resaco,$conresaco,'q15_anousu'))."','$this->q15_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q15_inscr"]))
           $resac = db_query("insert into db_acount values($acount,62,323,'".AddSlashes(pg_result($resaco,$conresaco,'q15_inscr'))."','$this->q15_inscr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q15_cadcal"]))
           $resac = db_query("insert into db_acount values($acount,62,318,'".AddSlashes(pg_result($resaco,$conresaco,'q15_cadcal'))."','$this->q15_cadcal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q15_recei"]))
           $resac = db_query("insert into db_acount values($acount,62,319,'".AddSlashes(pg_result($resaco,$conresaco,'q15_recei'))."','$this->q15_recei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q15_numpre"]))
           $resac = db_query("insert into db_acount values($acount,62,320,'".AddSlashes(pg_result($resaco,$conresaco,'q15_numpre'))."','$this->q15_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q15_valor"]))
           $resac = db_query("insert into db_acount values($acount,62,321,'".AddSlashes(pg_result($resaco,$conresaco,'q15_valor'))."','$this->q15_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q15_manual"]))
           $resac = db_query("insert into db_acount values($acount,62,6841,'".AddSlashes(pg_result($resaco,$conresaco,'q15_manual'))."','$this->q15_manual',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q15_anousu."-".$this->q15_inscr."-".$this->q15_cadcal."-".$this->q15_recei."-".$this->q15_numpre;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q15_anousu."-".$this->q15_inscr."-".$this->q15_cadcal."-".$this->q15_recei."-".$this->q15_numpre;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q15_anousu."-".$this->q15_inscr."-".$this->q15_cadcal."-".$this->q15_recei."-".$this->q15_numpre;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q15_anousu=null,$q15_inscr=null,$q15_cadcal=null,$q15_recei=null,$q15_numpre=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q15_anousu,$q15_inscr,$q15_cadcal,$q15_recei,$q15_numpre));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,316,'$q15_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,323,'$q15_inscr','E')");
         $resac = db_query("insert into db_acountkey values($acount,318,'$q15_cadcal','E')");
         $resac = db_query("insert into db_acountkey values($acount,319,'$q15_recei','E')");
         $resac = db_query("insert into db_acountkey values($acount,320,'$q15_numpre','E')");
         $resac = db_query("insert into db_acount values($acount,62,316,'','".AddSlashes(pg_result($resaco,$iresaco,'q15_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,62,323,'','".AddSlashes(pg_result($resaco,$iresaco,'q15_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,62,318,'','".AddSlashes(pg_result($resaco,$iresaco,'q15_cadcal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,62,319,'','".AddSlashes(pg_result($resaco,$iresaco,'q15_recei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,62,320,'','".AddSlashes(pg_result($resaco,$iresaco,'q15_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,62,321,'','".AddSlashes(pg_result($resaco,$iresaco,'q15_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,62,6841,'','".AddSlashes(pg_result($resaco,$iresaco,'q15_manual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from isscalcant
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q15_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q15_anousu = $q15_anousu ";
        }
        if($q15_inscr != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q15_inscr = $q15_inscr ";
        }
        if($q15_cadcal != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q15_cadcal = $q15_cadcal ";
        }
        if($q15_recei != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q15_recei = $q15_recei ";
        }
        if($q15_numpre != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q15_numpre = $q15_numpre ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q15_anousu."-".$q15_inscr."-".$q15_cadcal."-".$q15_recei."-".$q15_numpre;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q15_anousu."-".$q15_inscr."-".$q15_cadcal."-".$q15_recei."-".$q15_numpre;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q15_anousu."-".$q15_inscr."-".$q15_cadcal."-".$q15_recei."-".$q15_numpre;
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
        $this->erro_sql   = "Record Vazio na Tabela:isscalcant";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>