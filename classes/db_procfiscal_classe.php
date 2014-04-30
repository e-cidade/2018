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
//CLASSE DA ENTIDADE procfiscal
class cl_procfiscal { 
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
   var $y100_sequencial = 0; 
   var $y100_coddepto = 0; 
   var $y100_instit = 0; 
   var $y100_procfiscalcadtipo = 0; 
   var $y100_dtinicial_dia = null; 
   var $y100_dtinicial_mes = null; 
   var $y100_dtinicial_ano = null; 
   var $y100_dtinicial = null; 
   var $y100_dtfinal_dia = null; 
   var $y100_dtfinal_mes = null; 
   var $y100_dtfinal_ano = null; 
   var $y100_dtfinal = null; 
   var $y100_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 y100_sequencial = int4 = Processo Fiscal 
                 y100_coddepto = int4 = Depart. 
                 y100_instit = int4 = Instituição 
                 y100_procfiscalcadtipo = int4 = Tipo 
                 y100_dtinicial = date = Data inicial 
                 y100_dtfinal = date = Data final 
                 y100_obs = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_procfiscal() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("procfiscal"); 
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
       $this->y100_sequencial = ($this->y100_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["y100_sequencial"]:$this->y100_sequencial);
       $this->y100_coddepto = ($this->y100_coddepto == ""?@$GLOBALS["HTTP_POST_VARS"]["y100_coddepto"]:$this->y100_coddepto);
       $this->y100_instit = ($this->y100_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["y100_instit"]:$this->y100_instit);
       $this->y100_procfiscalcadtipo = ($this->y100_procfiscalcadtipo == ""?@$GLOBALS["HTTP_POST_VARS"]["y100_procfiscalcadtipo"]:$this->y100_procfiscalcadtipo);
       if($this->y100_dtinicial == ""){
         $this->y100_dtinicial_dia = ($this->y100_dtinicial_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["y100_dtinicial_dia"]:$this->y100_dtinicial_dia);
         $this->y100_dtinicial_mes = ($this->y100_dtinicial_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["y100_dtinicial_mes"]:$this->y100_dtinicial_mes);
         $this->y100_dtinicial_ano = ($this->y100_dtinicial_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["y100_dtinicial_ano"]:$this->y100_dtinicial_ano);
         if($this->y100_dtinicial_dia != ""){
            $this->y100_dtinicial = $this->y100_dtinicial_ano."-".$this->y100_dtinicial_mes."-".$this->y100_dtinicial_dia;
         }
       }
       if($this->y100_dtfinal == ""){
         $this->y100_dtfinal_dia = ($this->y100_dtfinal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["y100_dtfinal_dia"]:$this->y100_dtfinal_dia);
         $this->y100_dtfinal_mes = ($this->y100_dtfinal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["y100_dtfinal_mes"]:$this->y100_dtfinal_mes);
         $this->y100_dtfinal_ano = ($this->y100_dtfinal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["y100_dtfinal_ano"]:$this->y100_dtfinal_ano);
         if($this->y100_dtfinal_dia != ""){
            $this->y100_dtfinal = $this->y100_dtfinal_ano."-".$this->y100_dtfinal_mes."-".$this->y100_dtfinal_dia;
         }
       }
       $this->y100_obs = ($this->y100_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["y100_obs"]:$this->y100_obs);
     }else{
       $this->y100_sequencial = ($this->y100_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["y100_sequencial"]:$this->y100_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($y100_sequencial){ 
      $this->atualizacampos();
     if($this->y100_coddepto == null ){ 
       $this->erro_sql = " Campo Depart. nao Informado.";
       $this->erro_campo = "y100_coddepto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y100_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "y100_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y100_procfiscalcadtipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "y100_procfiscalcadtipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y100_dtinicial == null ){ 
       $this->erro_sql = " Campo Data inicial nao Informado.";
       $this->erro_campo = "y100_dtinicial_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y100_dtfinal == null ){ 
       $this->y100_dtfinal = "null";
     }
     if($this->y100_obs == null ){ 
       $this->erro_sql = " Campo Observação nao Informado.";
       $this->erro_campo = "y100_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($y100_sequencial == "" || $y100_sequencial == null ){
       $result = db_query("select nextval('procfiscal_y100_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: procfiscal_y100_sequencial_seq do campo: y100_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->y100_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from procfiscal_y100_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $y100_sequencial)){
         $this->erro_sql = " Campo y100_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->y100_sequencial = $y100_sequencial; 
       }
     }
     if(($this->y100_sequencial == null) || ($this->y100_sequencial == "") ){ 
       $this->erro_sql = " Campo y100_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into procfiscal(
                                       y100_sequencial 
                                      ,y100_coddepto 
                                      ,y100_instit 
                                      ,y100_procfiscalcadtipo 
                                      ,y100_dtinicial 
                                      ,y100_dtfinal 
                                      ,y100_obs 
                       )
                values (
                                $this->y100_sequencial 
                               ,$this->y100_coddepto 
                               ,$this->y100_instit 
                               ,$this->y100_procfiscalcadtipo 
                               ,".($this->y100_dtinicial == "null" || $this->y100_dtinicial == ""?"null":"'".$this->y100_dtinicial."'")." 
                               ,".($this->y100_dtfinal == "null" || $this->y100_dtfinal == ""?"null":"'".$this->y100_dtfinal."'")." 
                               ,'$this->y100_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "procfiscal ($this->y100_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "procfiscal já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "procfiscal ($this->y100_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y100_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->y100_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12019,'$this->y100_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2080,12019,'','".AddSlashes(pg_result($resaco,0,'y100_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2080,12020,'','".AddSlashes(pg_result($resaco,0,'y100_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2080,12021,'','".AddSlashes(pg_result($resaco,0,'y100_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2080,12025,'','".AddSlashes(pg_result($resaco,0,'y100_procfiscalcadtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2080,12026,'','".AddSlashes(pg_result($resaco,0,'y100_dtinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2080,12027,'','".AddSlashes(pg_result($resaco,0,'y100_dtfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2080,12028,'','".AddSlashes(pg_result($resaco,0,'y100_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($y100_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update procfiscal set ";
     $virgula = "";
     if(trim($this->y100_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y100_sequencial"])){ 
       $sql  .= $virgula." y100_sequencial = $this->y100_sequencial ";
       $virgula = ",";
       if(trim($this->y100_sequencial) == null ){ 
         $this->erro_sql = " Campo Processo Fiscal nao Informado.";
         $this->erro_campo = "y100_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y100_coddepto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y100_coddepto"])){ 
       $sql  .= $virgula." y100_coddepto = $this->y100_coddepto ";
       $virgula = ",";
       if(trim($this->y100_coddepto) == null ){ 
         $this->erro_sql = " Campo Depart. nao Informado.";
         $this->erro_campo = "y100_coddepto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y100_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y100_instit"])){ 
       $sql  .= $virgula." y100_instit = $this->y100_instit ";
       $virgula = ",";
       if(trim($this->y100_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "y100_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y100_procfiscalcadtipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y100_procfiscalcadtipo"])){ 
       $sql  .= $virgula." y100_procfiscalcadtipo = $this->y100_procfiscalcadtipo ";
       $virgula = ",";
       if(trim($this->y100_procfiscalcadtipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "y100_procfiscalcadtipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y100_dtinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y100_dtinicial_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["y100_dtinicial_dia"] !="") ){ 
       $sql  .= $virgula." y100_dtinicial = '$this->y100_dtinicial' ";
       $virgula = ",";
       if(trim($this->y100_dtinicial) == null ){ 
         $this->erro_sql = " Campo Data inicial nao Informado.";
         $this->erro_campo = "y100_dtinicial_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["y100_dtinicial_dia"])){ 
         $sql  .= $virgula." y100_dtinicial = null ";
         $virgula = ",";
         if(trim($this->y100_dtinicial) == null ){ 
           $this->erro_sql = " Campo Data inicial nao Informado.";
           $this->erro_campo = "y100_dtinicial_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->y100_dtfinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y100_dtfinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["y100_dtfinal_dia"] !="") ){ 
       $sql  .= $virgula." y100_dtfinal = '$this->y100_dtfinal' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["y100_dtfinal_dia"])){ 
         $sql  .= $virgula." y100_dtfinal = null ";
         $virgula = ",";
       }
     }
     if(trim($this->y100_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y100_obs"])){ 
       $sql  .= $virgula." y100_obs = '$this->y100_obs' ";
       $virgula = ",";
       if(trim($this->y100_obs) == null ){ 
         $this->erro_sql = " Campo Observação nao Informado.";
         $this->erro_campo = "y100_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($y100_sequencial!=null){
       $sql .= " y100_sequencial = $this->y100_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->y100_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12019,'$this->y100_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y100_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2080,12019,'".AddSlashes(pg_result($resaco,$conresaco,'y100_sequencial'))."','$this->y100_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y100_coddepto"]))
           $resac = db_query("insert into db_acount values($acount,2080,12020,'".AddSlashes(pg_result($resaco,$conresaco,'y100_coddepto'))."','$this->y100_coddepto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y100_instit"]))
           $resac = db_query("insert into db_acount values($acount,2080,12021,'".AddSlashes(pg_result($resaco,$conresaco,'y100_instit'))."','$this->y100_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y100_procfiscalcadtipo"]))
           $resac = db_query("insert into db_acount values($acount,2080,12025,'".AddSlashes(pg_result($resaco,$conresaco,'y100_procfiscalcadtipo'))."','$this->y100_procfiscalcadtipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y100_dtinicial"]))
           $resac = db_query("insert into db_acount values($acount,2080,12026,'".AddSlashes(pg_result($resaco,$conresaco,'y100_dtinicial'))."','$this->y100_dtinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y100_dtfinal"]))
           $resac = db_query("insert into db_acount values($acount,2080,12027,'".AddSlashes(pg_result($resaco,$conresaco,'y100_dtfinal'))."','$this->y100_dtfinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y100_obs"]))
           $resac = db_query("insert into db_acount values($acount,2080,12028,'".AddSlashes(pg_result($resaco,$conresaco,'y100_obs'))."','$this->y100_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "procfiscal nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->y100_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "procfiscal nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->y100_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y100_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($y100_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($y100_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12019,'$y100_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2080,12019,'','".AddSlashes(pg_result($resaco,$iresaco,'y100_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2080,12020,'','".AddSlashes(pg_result($resaco,$iresaco,'y100_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2080,12021,'','".AddSlashes(pg_result($resaco,$iresaco,'y100_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2080,12025,'','".AddSlashes(pg_result($resaco,$iresaco,'y100_procfiscalcadtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2080,12026,'','".AddSlashes(pg_result($resaco,$iresaco,'y100_dtinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2080,12027,'','".AddSlashes(pg_result($resaco,$iresaco,'y100_dtfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2080,12028,'','".AddSlashes(pg_result($resaco,$iresaco,'y100_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from procfiscal
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($y100_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y100_sequencial = $y100_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "procfiscal nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$y100_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "procfiscal nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$y100_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$y100_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:procfiscal";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $y100_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from procfiscal ";
     $sql .= "      inner join db_config  on  db_config.codigo = procfiscal.y100_instit";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = procfiscal.y100_coddepto";
     $sql .= "      inner join procfiscalcadtipo  on  procfiscalcadtipo.y33_sequencial = procfiscal.y100_procfiscalcadtipo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_config c on  c.codigo = db_depart.instit";
     $sql2 = "";
     if($dbwhere==""){
       if($y100_sequencial!=null ){
         $sql2 .= " where procfiscal.y100_sequencial = $y100_sequencial "; 
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
   function sql_query_file ( $y100_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from procfiscal ";
     $sql2 = "";
     if($dbwhere==""){
       if($y100_sequencial!=null ){
         $sql2 .= " where procfiscal.y100_sequencial = $y100_sequencial "; 
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