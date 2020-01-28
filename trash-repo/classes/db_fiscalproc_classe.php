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

//MODULO: fiscal
//CLASSE DA ENTIDADE fiscalproc
class cl_fiscalproc { 
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
   var $y29_codtipo = 0; 
   var $y29_descr = null; 
   var $y29_coddepto = 0; 
   var $y29_tipoandam = 0; 
   var $y29_descr_obs = null; 
   var $y29_dias = 0; 
   var $y29_tipoproced = null; 
   var $y29_tipofisc = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 y29_codtipo = int8 = Código da Procedência 
                 y29_descr = varchar(50) = Descrição da Procedência 
                 y29_coddepto = int4 = Código do Departamento 
                 y29_tipoandam = int8 = Tipo de Andamento 
                 y29_descr_obs = text = Descrição 
                 y29_dias = int4 = Quantidade de dias para o vencimento 
                 y29_tipoproced = varchar(1) = Tipo de Procedimento 
                 y29_tipofisc = int4 = Tipo de Fiscalização 
                 ";
   //funcao construtor da classe 
   function cl_fiscalproc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("fiscalproc"); 
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
       $this->y29_codtipo = ($this->y29_codtipo == ""?@$GLOBALS["HTTP_POST_VARS"]["y29_codtipo"]:$this->y29_codtipo);
       $this->y29_descr = ($this->y29_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["y29_descr"]:$this->y29_descr);
       $this->y29_coddepto = ($this->y29_coddepto == ""?@$GLOBALS["HTTP_POST_VARS"]["y29_coddepto"]:$this->y29_coddepto);
       $this->y29_tipoandam = ($this->y29_tipoandam == ""?@$GLOBALS["HTTP_POST_VARS"]["y29_tipoandam"]:$this->y29_tipoandam);
       $this->y29_descr_obs = ($this->y29_descr_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["y29_descr_obs"]:$this->y29_descr_obs);
       $this->y29_dias = ($this->y29_dias == ""?@$GLOBALS["HTTP_POST_VARS"]["y29_dias"]:$this->y29_dias);
       $this->y29_tipoproced = ($this->y29_tipoproced == ""?@$GLOBALS["HTTP_POST_VARS"]["y29_tipoproced"]:$this->y29_tipoproced);
       $this->y29_tipofisc = ($this->y29_tipofisc == ""?@$GLOBALS["HTTP_POST_VARS"]["y29_tipofisc"]:$this->y29_tipofisc);
     }else{
       $this->y29_codtipo = ($this->y29_codtipo == ""?@$GLOBALS["HTTP_POST_VARS"]["y29_codtipo"]:$this->y29_codtipo);
     }
   }
   // funcao para inclusao
   function incluir ($y29_codtipo){ 
      $this->atualizacampos();
     if($this->y29_descr == null ){ 
       $this->erro_sql = " Campo Descrição da Procedência nao Informado.";
       $this->erro_campo = "y29_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y29_coddepto == null ){ 
       $this->erro_sql = " Campo Código do Departamento nao Informado.";
       $this->erro_campo = "y29_coddepto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y29_tipoandam == null ){ 
       $this->erro_sql = " Campo Tipo de Andamento nao Informado.";
       $this->erro_campo = "y29_tipoandam";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y29_descr_obs == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "y29_descr_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y29_dias == null ){ 
       $this->y29_dias = "0";
     }
     if($this->y29_tipoproced == null ){ 
       $this->erro_sql = " Campo Tipo de Procedimento nao Informado.";
       $this->erro_campo = "y29_tipoproced";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y29_tipofisc == null ){ 
       $this->erro_sql = " Campo Tipo de Fiscalização nao Informado.";
       $this->erro_campo = "y29_tipofisc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($y29_codtipo == "" || $y29_codtipo == null ){
       $result = db_query("select nextval('fiscalproc_y29_codtipo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: fiscalproc_y29_codtipo_seq do campo: y29_codtipo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->y29_codtipo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from fiscalproc_y29_codtipo_seq");
       if(($result != false) && (pg_result($result,0,0) < $y29_codtipo)){
         $this->erro_sql = " Campo y29_codtipo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->y29_codtipo = $y29_codtipo; 
       }
     }
     if(($this->y29_codtipo == null) || ($this->y29_codtipo == "") ){ 
       $this->erro_sql = " Campo y29_codtipo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into fiscalproc(
                                       y29_codtipo 
                                      ,y29_descr 
                                      ,y29_coddepto 
                                      ,y29_tipoandam 
                                      ,y29_descr_obs 
                                      ,y29_dias 
                                      ,y29_tipoproced 
                                      ,y29_tipofisc 
                       )
                values (
                                $this->y29_codtipo 
                               ,'$this->y29_descr' 
                               ,$this->y29_coddepto 
                               ,$this->y29_tipoandam 
                               ,'$this->y29_descr_obs' 
                               ,$this->y29_dias 
                               ,'$this->y29_tipoproced' 
                               ,$this->y29_tipofisc 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "fiscalproc ($this->y29_codtipo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "fiscalproc já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "fiscalproc ($this->y29_codtipo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y29_codtipo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->y29_codtipo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4933,'$this->y29_codtipo','I')");
       $resac = db_query("insert into db_acount values($acount,681,4933,'','".AddSlashes(pg_result($resaco,0,'y29_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,681,4934,'','".AddSlashes(pg_result($resaco,0,'y29_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,681,4935,'','".AddSlashes(pg_result($resaco,0,'y29_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,681,4936,'','".AddSlashes(pg_result($resaco,0,'y29_tipoandam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,681,5189,'','".AddSlashes(pg_result($resaco,0,'y29_descr_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,681,5197,'','".AddSlashes(pg_result($resaco,0,'y29_dias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,681,6786,'','".AddSlashes(pg_result($resaco,0,'y29_tipoproced'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,681,6791,'','".AddSlashes(pg_result($resaco,0,'y29_tipofisc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($y29_codtipo=null) { 
      $this->atualizacampos();
     $sql = " update fiscalproc set ";
     $virgula = "";
     if(trim($this->y29_codtipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y29_codtipo"])){ 
       $sql  .= $virgula." y29_codtipo = $this->y29_codtipo ";
       $virgula = ",";
       if(trim($this->y29_codtipo) == null ){ 
         $this->erro_sql = " Campo Código da Procedência nao Informado.";
         $this->erro_campo = "y29_codtipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y29_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y29_descr"])){ 
       $sql  .= $virgula." y29_descr = '$this->y29_descr' ";
       $virgula = ",";
       if(trim($this->y29_descr) == null ){ 
         $this->erro_sql = " Campo Descrição da Procedência nao Informado.";
         $this->erro_campo = "y29_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y29_coddepto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y29_coddepto"])){ 
       $sql  .= $virgula." y29_coddepto = $this->y29_coddepto ";
       $virgula = ",";
       if(trim($this->y29_coddepto) == null ){ 
         $this->erro_sql = " Campo Código do Departamento nao Informado.";
         $this->erro_campo = "y29_coddepto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y29_tipoandam)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y29_tipoandam"])){ 
       $sql  .= $virgula." y29_tipoandam = $this->y29_tipoandam ";
       $virgula = ",";
       if(trim($this->y29_tipoandam) == null ){ 
         $this->erro_sql = " Campo Tipo de Andamento nao Informado.";
         $this->erro_campo = "y29_tipoandam";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y29_descr_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y29_descr_obs"])){ 
       $sql  .= $virgula." y29_descr_obs = '$this->y29_descr_obs' ";
       $virgula = ",";
       if(trim($this->y29_descr_obs) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "y29_descr_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y29_dias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y29_dias"])){ 
        if(trim($this->y29_dias)=="" && isset($GLOBALS["HTTP_POST_VARS"]["y29_dias"])){ 
           $this->y29_dias = "0" ; 
        } 
       $sql  .= $virgula." y29_dias = $this->y29_dias ";
       $virgula = ",";
     }
     if(trim($this->y29_tipoproced)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y29_tipoproced"])){ 
       $sql  .= $virgula." y29_tipoproced = '$this->y29_tipoproced' ";
       $virgula = ",";
       if(trim($this->y29_tipoproced) == null ){ 
         $this->erro_sql = " Campo Tipo de Procedimento nao Informado.";
         $this->erro_campo = "y29_tipoproced";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y29_tipofisc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y29_tipofisc"])){ 
       $sql  .= $virgula." y29_tipofisc = $this->y29_tipofisc ";
       $virgula = ",";
       if(trim($this->y29_tipofisc) == null ){ 
         $this->erro_sql = " Campo Tipo de Fiscalização nao Informado.";
         $this->erro_campo = "y29_tipofisc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($y29_codtipo!=null){
       $sql .= " y29_codtipo = $this->y29_codtipo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->y29_codtipo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4933,'$this->y29_codtipo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y29_codtipo"]))
           $resac = db_query("insert into db_acount values($acount,681,4933,'".AddSlashes(pg_result($resaco,$conresaco,'y29_codtipo'))."','$this->y29_codtipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y29_descr"]))
           $resac = db_query("insert into db_acount values($acount,681,4934,'".AddSlashes(pg_result($resaco,$conresaco,'y29_descr'))."','$this->y29_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y29_coddepto"]))
           $resac = db_query("insert into db_acount values($acount,681,4935,'".AddSlashes(pg_result($resaco,$conresaco,'y29_coddepto'))."','$this->y29_coddepto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y29_tipoandam"]))
           $resac = db_query("insert into db_acount values($acount,681,4936,'".AddSlashes(pg_result($resaco,$conresaco,'y29_tipoandam'))."','$this->y29_tipoandam',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y29_descr_obs"]))
           $resac = db_query("insert into db_acount values($acount,681,5189,'".AddSlashes(pg_result($resaco,$conresaco,'y29_descr_obs'))."','$this->y29_descr_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y29_dias"]))
           $resac = db_query("insert into db_acount values($acount,681,5197,'".AddSlashes(pg_result($resaco,$conresaco,'y29_dias'))."','$this->y29_dias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y29_tipoproced"]))
           $resac = db_query("insert into db_acount values($acount,681,6786,'".AddSlashes(pg_result($resaco,$conresaco,'y29_tipoproced'))."','$this->y29_tipoproced',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y29_tipofisc"]))
           $resac = db_query("insert into db_acount values($acount,681,6791,'".AddSlashes(pg_result($resaco,$conresaco,'y29_tipofisc'))."','$this->y29_tipofisc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "fiscalproc nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->y29_codtipo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "fiscalproc nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->y29_codtipo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y29_codtipo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($y29_codtipo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($y29_codtipo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4933,'$y29_codtipo','E')");
         $resac = db_query("insert into db_acount values($acount,681,4933,'','".AddSlashes(pg_result($resaco,$iresaco,'y29_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,681,4934,'','".AddSlashes(pg_result($resaco,$iresaco,'y29_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,681,4935,'','".AddSlashes(pg_result($resaco,$iresaco,'y29_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,681,4936,'','".AddSlashes(pg_result($resaco,$iresaco,'y29_tipoandam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,681,5189,'','".AddSlashes(pg_result($resaco,$iresaco,'y29_descr_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,681,5197,'','".AddSlashes(pg_result($resaco,$iresaco,'y29_dias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,681,6786,'','".AddSlashes(pg_result($resaco,$iresaco,'y29_tipoproced'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,681,6791,'','".AddSlashes(pg_result($resaco,$iresaco,'y29_tipofisc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from fiscalproc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($y29_codtipo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y29_codtipo = $y29_codtipo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "fiscalproc nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$y29_codtipo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "fiscalproc nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$y29_codtipo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$y29_codtipo;
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
        $this->erro_sql   = "Record Vazio na Tabela:fiscalproc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $y29_codtipo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from fiscalproc ";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = fiscalproc.y29_coddepto";
     $sql .= "      inner join tipoandam  on  tipoandam.y41_codtipo = fiscalproc.y29_tipoandam";
     $sql .= "      inner join tipofiscaliza  on  tipofiscaliza.y27_codtipo = fiscalproc.y29_tipofisc";
     $sql .= "      left outer join fiscalprocpa on  y29_codtipo = y61_codpa";
     $sql2 = "";
     if($dbwhere==""){
       if($y29_codtipo!=null ){
         $sql2 .= " where fiscalproc.y29_codtipo = $y29_codtipo "; 
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
   function sql_query_file ( $y29_codtipo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from fiscalproc ";
     $sql2 = "";
     if($dbwhere==""){
       if($y29_codtipo!=null ){
         $sql2 .= " where fiscalproc.y29_codtipo = $y29_codtipo "; 
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
   function sql_query_rec ( $y29_codtipo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from fiscalproc ";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = fiscalproc.y29_coddepto";
     $sql .= "      inner join tipoandam  on  tipoandam.y41_codtipo = fiscalproc.y29_tipoandam";
     $sql .= "      left join fiscalprocrec  on  fiscalprocrec.y45_codtipo = fiscalproc.y29_codtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($y29_codtipo!=null ){
         $sql2 .= " where fiscalproc.y29_codtipo = $y29_codtipo ";
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
     }    return $sql;
  }
}
?>