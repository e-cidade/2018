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
//CLASSE DA ENTIDADE procfiscalfases
class cl_procfiscalfases { 
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
   var $y108_sequencial = 0; 
   var $y108_procfiscal = 0; 
   var $y108_dtcriacao_dia = null; 
   var $y108_dtcriacao_mes = null; 
   var $y108_dtcriacao_ano = null; 
   var $y108_dtcriacao = null; 
   var $y108_dtassinatura_dia = null; 
   var $y108_dtassinatura_mes = null; 
   var $y108_dtassinatura_ano = null; 
   var $y108_dtassinatura = null; 
   var $y108_responsavel = 0; 
   var $y108_tipo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 y108_sequencial = int4 = Código 
                 y108_procfiscal = int4 = Processo Fiscal 
                 y108_dtcriacao = date = Data de Criação 
                 y108_dtassinatura = date = Data da assinatura 
                 y108_responsavel = int4 = Responsavel 
                 y108_tipo = int4 = Tipo 
                 ";
   //funcao construtor da classe 
   function cl_procfiscalfases() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("procfiscalfases"); 
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
       $this->y108_sequencial = ($this->y108_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["y108_sequencial"]:$this->y108_sequencial);
       $this->y108_procfiscal = ($this->y108_procfiscal == ""?@$GLOBALS["HTTP_POST_VARS"]["y108_procfiscal"]:$this->y108_procfiscal);
       if($this->y108_dtcriacao == ""){
         $this->y108_dtcriacao_dia = ($this->y108_dtcriacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["y108_dtcriacao_dia"]:$this->y108_dtcriacao_dia);
         $this->y108_dtcriacao_mes = ($this->y108_dtcriacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["y108_dtcriacao_mes"]:$this->y108_dtcriacao_mes);
         $this->y108_dtcriacao_ano = ($this->y108_dtcriacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["y108_dtcriacao_ano"]:$this->y108_dtcriacao_ano);
         if($this->y108_dtcriacao_dia != ""){
            $this->y108_dtcriacao = $this->y108_dtcriacao_ano."-".$this->y108_dtcriacao_mes."-".$this->y108_dtcriacao_dia;
         }
       }
       if($this->y108_dtassinatura == ""){
         $this->y108_dtassinatura_dia = ($this->y108_dtassinatura_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["y108_dtassinatura_dia"]:$this->y108_dtassinatura_dia);
         $this->y108_dtassinatura_mes = ($this->y108_dtassinatura_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["y108_dtassinatura_mes"]:$this->y108_dtassinatura_mes);
         $this->y108_dtassinatura_ano = ($this->y108_dtassinatura_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["y108_dtassinatura_ano"]:$this->y108_dtassinatura_ano);
         if($this->y108_dtassinatura_dia != ""){
            $this->y108_dtassinatura = $this->y108_dtassinatura_ano."-".$this->y108_dtassinatura_mes."-".$this->y108_dtassinatura_dia;
         }
       }
       $this->y108_responsavel = ($this->y108_responsavel == ""?@$GLOBALS["HTTP_POST_VARS"]["y108_responsavel"]:$this->y108_responsavel);
       $this->y108_tipo = ($this->y108_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["y108_tipo"]:$this->y108_tipo);
     }else{
       $this->y108_sequencial = ($this->y108_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["y108_sequencial"]:$this->y108_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($y108_sequencial){ 
      $this->atualizacampos();
     if($this->y108_procfiscal == null ){ 
       $this->erro_sql = " Campo Processo Fiscal nao Informado.";
       $this->erro_campo = "y108_procfiscal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y108_dtcriacao == null ){ 
       $this->erro_sql = " Campo Data de Criação nao Informado.";
       $this->erro_campo = "y108_dtcriacao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y108_dtassinatura == null ){ 
       $this->erro_sql = " Campo Data da assinatura nao Informado.";
       $this->erro_campo = "y108_dtassinatura_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y108_responsavel == null ){ 
       $this->erro_sql = " Campo Responsavel nao Informado.";
       $this->erro_campo = "y108_responsavel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y108_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "y108_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($y108_sequencial == "" || $y108_sequencial == null ){
       $result = db_query("select nextval('procfiscalfases_y108_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: procfiscalfases_y108_sequencial_seq do campo: y108_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->y108_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from procfiscalfases_y108_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $y108_sequencial)){
         $this->erro_sql = " Campo y108_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->y108_sequencial = $y108_sequencial; 
       }
     }
     if(($this->y108_sequencial == null) || ($this->y108_sequencial == "") ){ 
       $this->erro_sql = " Campo y108_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into procfiscalfases(
                                       y108_sequencial 
                                      ,y108_procfiscal 
                                      ,y108_dtcriacao 
                                      ,y108_dtassinatura 
                                      ,y108_responsavel 
                                      ,y108_tipo 
                       )
                values (
                                $this->y108_sequencial 
                               ,$this->y108_procfiscal 
                               ,".($this->y108_dtcriacao == "null" || $this->y108_dtcriacao == ""?"null":"'".$this->y108_dtcriacao."'")." 
                               ,".($this->y108_dtassinatura == "null" || $this->y108_dtassinatura == ""?"null":"'".$this->y108_dtassinatura."'")." 
                               ,$this->y108_responsavel 
                               ,$this->y108_tipo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "procfiscalfases ($this->y108_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "procfiscalfases já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "procfiscalfases ($this->y108_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y108_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->y108_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12047,'$this->y108_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2088,12047,'','".AddSlashes(pg_result($resaco,0,'y108_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2088,12048,'','".AddSlashes(pg_result($resaco,0,'y108_procfiscal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2088,12049,'','".AddSlashes(pg_result($resaco,0,'y108_dtcriacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2088,12052,'','".AddSlashes(pg_result($resaco,0,'y108_dtassinatura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2088,12050,'','".AddSlashes(pg_result($resaco,0,'y108_responsavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2088,12051,'','".AddSlashes(pg_result($resaco,0,'y108_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($y108_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update procfiscalfases set ";
     $virgula = "";
     if(trim($this->y108_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y108_sequencial"])){ 
       $sql  .= $virgula." y108_sequencial = $this->y108_sequencial ";
       $virgula = ",";
       if(trim($this->y108_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "y108_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y108_procfiscal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y108_procfiscal"])){ 
       $sql  .= $virgula." y108_procfiscal = $this->y108_procfiscal ";
       $virgula = ",";
       if(trim($this->y108_procfiscal) == null ){ 
         $this->erro_sql = " Campo Processo Fiscal nao Informado.";
         $this->erro_campo = "y108_procfiscal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y108_dtcriacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y108_dtcriacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["y108_dtcriacao_dia"] !="") ){ 
       $sql  .= $virgula." y108_dtcriacao = '$this->y108_dtcriacao' ";
       $virgula = ",";
       if(trim($this->y108_dtcriacao) == null ){ 
         $this->erro_sql = " Campo Data de Criação nao Informado.";
         $this->erro_campo = "y108_dtcriacao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["y108_dtcriacao_dia"])){ 
         $sql  .= $virgula." y108_dtcriacao = null ";
         $virgula = ",";
         if(trim($this->y108_dtcriacao) == null ){ 
           $this->erro_sql = " Campo Data de Criação nao Informado.";
           $this->erro_campo = "y108_dtcriacao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->y108_dtassinatura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y108_dtassinatura_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["y108_dtassinatura_dia"] !="") ){ 
       $sql  .= $virgula." y108_dtassinatura = '$this->y108_dtassinatura' ";
       $virgula = ",";
       if(trim($this->y108_dtassinatura) == null ){ 
         $this->erro_sql = " Campo Data da assinatura nao Informado.";
         $this->erro_campo = "y108_dtassinatura_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["y108_dtassinatura_dia"])){ 
         $sql  .= $virgula." y108_dtassinatura = null ";
         $virgula = ",";
         if(trim($this->y108_dtassinatura) == null ){ 
           $this->erro_sql = " Campo Data da assinatura nao Informado.";
           $this->erro_campo = "y108_dtassinatura_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->y108_responsavel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y108_responsavel"])){ 
       $sql  .= $virgula." y108_responsavel = $this->y108_responsavel ";
       $virgula = ",";
       if(trim($this->y108_responsavel) == null ){ 
         $this->erro_sql = " Campo Responsavel nao Informado.";
         $this->erro_campo = "y108_responsavel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y108_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y108_tipo"])){ 
       $sql  .= $virgula." y108_tipo = $this->y108_tipo ";
       $virgula = ",";
       if(trim($this->y108_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "y108_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($y108_sequencial!=null){
       $sql .= " y108_sequencial = $this->y108_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->y108_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12047,'$this->y108_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y108_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2088,12047,'".AddSlashes(pg_result($resaco,$conresaco,'y108_sequencial'))."','$this->y108_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y108_procfiscal"]))
           $resac = db_query("insert into db_acount values($acount,2088,12048,'".AddSlashes(pg_result($resaco,$conresaco,'y108_procfiscal'))."','$this->y108_procfiscal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y108_dtcriacao"]))
           $resac = db_query("insert into db_acount values($acount,2088,12049,'".AddSlashes(pg_result($resaco,$conresaco,'y108_dtcriacao'))."','$this->y108_dtcriacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y108_dtassinatura"]))
           $resac = db_query("insert into db_acount values($acount,2088,12052,'".AddSlashes(pg_result($resaco,$conresaco,'y108_dtassinatura'))."','$this->y108_dtassinatura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y108_responsavel"]))
           $resac = db_query("insert into db_acount values($acount,2088,12050,'".AddSlashes(pg_result($resaco,$conresaco,'y108_responsavel'))."','$this->y108_responsavel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y108_tipo"]))
           $resac = db_query("insert into db_acount values($acount,2088,12051,'".AddSlashes(pg_result($resaco,$conresaco,'y108_tipo'))."','$this->y108_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "procfiscalfases nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->y108_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "procfiscalfases nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->y108_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y108_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($y108_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($y108_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12047,'$y108_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2088,12047,'','".AddSlashes(pg_result($resaco,$iresaco,'y108_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2088,12048,'','".AddSlashes(pg_result($resaco,$iresaco,'y108_procfiscal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2088,12049,'','".AddSlashes(pg_result($resaco,$iresaco,'y108_dtcriacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2088,12052,'','".AddSlashes(pg_result($resaco,$iresaco,'y108_dtassinatura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2088,12050,'','".AddSlashes(pg_result($resaco,$iresaco,'y108_responsavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2088,12051,'','".AddSlashes(pg_result($resaco,$iresaco,'y108_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from procfiscalfases
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($y108_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y108_sequencial = $y108_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "procfiscalfases nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$y108_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "procfiscalfases nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$y108_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$y108_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:procfiscalfases";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $y108_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from procfiscalfases ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = procfiscalfases.y108_responsavel";
     $sql .= "      inner join procfiscal  on  procfiscal.y100_sequencial = procfiscalfases.y108_procfiscal";
     $sql .= "      inner join db_config  on  db_config.codigo = procfiscal.y100_instit";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = procfiscal.y100_coddepto";
     $sql .= "      inner join procfiscalcadtipo  on  procfiscalcadtipo.y33_sequencial = procfiscal.y100_procfiscalcadtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($y108_sequencial!=null ){
         $sql2 .= " where procfiscalfases.y108_sequencial = $y108_sequencial "; 
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
   function sql_query_file ( $y108_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from procfiscalfases ";
     $sql2 = "";
     if($dbwhere==""){
       if($y108_sequencial!=null ){
         $sql2 .= " where procfiscalfases.y108_sequencial = $y108_sequencial "; 
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