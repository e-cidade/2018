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

//MODULO: agua
//CLASSE DA ENTIDADE aguacortetipodebito
class cl_aguacortetipodebito { 
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
   var $x45_codcorte = 0; 
   var $x45_tipo = 0; 
   var $x45_parcelas = 0; 
   var $x45_dtvenc_dia = null; 
   var $x45_dtvenc_mes = null; 
   var $x45_dtvenc_ano = null; 
   var $x45_dtvenc = null; 
   var $x45_vlrminimo = 0; 
   var $x45_codcortetipodebito = 0; 
   var $x45_dtopini_dia = null; 
   var $x45_dtopini_mes = null; 
   var $x45_dtopini_ano = null; 
   var $x45_dtopini = null; 
   var $x45_dtopfim_dia = null; 
   var $x45_dtopfim_mes = null; 
   var $x45_dtopfim_ano = null; 
   var $x45_dtopfim = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 x45_codcorte = int4 = Corte 
                 x45_tipo = int4 = Débito 
                 x45_parcelas = int4 = Parcelas Atraso 
                 x45_dtvenc = date = Vencimento 
                 x45_vlrminimo = float8 = Valor Mínimo 
                 x45_codcortetipodebito = int4 = Corte Tipo Debito 
                 x45_dtopini = date = Data Inicial Operação 
                 x45_dtopfim = date = Data Final Operação 
                 ";
   //funcao construtor da classe 
   function cl_aguacortetipodebito() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguacortetipodebito"); 
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
       $this->x45_codcorte = ($this->x45_codcorte == ""?@$GLOBALS["HTTP_POST_VARS"]["x45_codcorte"]:$this->x45_codcorte);
       $this->x45_tipo = ($this->x45_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["x45_tipo"]:$this->x45_tipo);
       $this->x45_parcelas = ($this->x45_parcelas == ""?@$GLOBALS["HTTP_POST_VARS"]["x45_parcelas"]:$this->x45_parcelas);
       if($this->x45_dtvenc == ""){
         $this->x45_dtvenc_dia = ($this->x45_dtvenc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["x45_dtvenc_dia"]:$this->x45_dtvenc_dia);
         $this->x45_dtvenc_mes = ($this->x45_dtvenc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["x45_dtvenc_mes"]:$this->x45_dtvenc_mes);
         $this->x45_dtvenc_ano = ($this->x45_dtvenc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["x45_dtvenc_ano"]:$this->x45_dtvenc_ano);
         if($this->x45_dtvenc_dia != ""){
            $this->x45_dtvenc = $this->x45_dtvenc_ano."-".$this->x45_dtvenc_mes."-".$this->x45_dtvenc_dia;
         }
       }
       $this->x45_vlrminimo = ($this->x45_vlrminimo == ""?@$GLOBALS["HTTP_POST_VARS"]["x45_vlrminimo"]:$this->x45_vlrminimo);
       $this->x45_codcortetipodebito = ($this->x45_codcortetipodebito == ""?@$GLOBALS["HTTP_POST_VARS"]["x45_codcortetipodebito"]:$this->x45_codcortetipodebito);
       if($this->x45_dtopini == ""){
         $this->x45_dtopini_dia = ($this->x45_dtopini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["x45_dtopini_dia"]:$this->x45_dtopini_dia);
         $this->x45_dtopini_mes = ($this->x45_dtopini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["x45_dtopini_mes"]:$this->x45_dtopini_mes);
         $this->x45_dtopini_ano = ($this->x45_dtopini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["x45_dtopini_ano"]:$this->x45_dtopini_ano);
         if($this->x45_dtopini_dia != ""){
            $this->x45_dtopini = $this->x45_dtopini_ano."-".$this->x45_dtopini_mes."-".$this->x45_dtopini_dia;
         }
       }
       if($this->x45_dtopfim == ""){
         $this->x45_dtopfim_dia = ($this->x45_dtopfim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["x45_dtopfim_dia"]:$this->x45_dtopfim_dia);
         $this->x45_dtopfim_mes = ($this->x45_dtopfim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["x45_dtopfim_mes"]:$this->x45_dtopfim_mes);
         $this->x45_dtopfim_ano = ($this->x45_dtopfim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["x45_dtopfim_ano"]:$this->x45_dtopfim_ano);
         if($this->x45_dtopfim_dia != ""){
            $this->x45_dtopfim = $this->x45_dtopfim_ano."-".$this->x45_dtopfim_mes."-".$this->x45_dtopfim_dia;
         }
       }
     }else{
       $this->x45_codcorte = ($this->x45_codcorte == ""?@$GLOBALS["HTTP_POST_VARS"]["x45_codcorte"]:$this->x45_codcorte);
       $this->x45_codcortetipodebito = ($this->x45_codcortetipodebito == ""?@$GLOBALS["HTTP_POST_VARS"]["x45_codcortetipodebito"]:$this->x45_codcortetipodebito);
     }
   }
   // funcao para inclusao
   function incluir ($x45_codcortetipodebito){ 
      $this->atualizacampos();
     if($this->x45_tipo == null ){ 
       $this->erro_sql = " Campo Débito nao Informado.";
       $this->erro_campo = "x45_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x45_parcelas == null ){ 
       $this->erro_sql = " Campo Parcelas Atraso nao Informado.";
       $this->erro_campo = "x45_parcelas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x45_dtvenc == null ){ 
       $this->erro_sql = " Campo Vencimento nao Informado.";
       $this->erro_campo = "x45_dtvenc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x45_vlrminimo == null ){ 
       $this->x45_vlrminimo = "0";
     }
     if($this->x45_dtopini == null ){ 
       $this->x45_dtopini = "null";
     }
     if($this->x45_dtopfim == null ){ 
       $this->x45_dtopfim = "null";
     }
     if($x45_codcortetipodebito == "" || $x45_codcortetipodebito == null ){
       $result = db_query("select nextval('aguacortetipodebito_x45_codcortetipodebito_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: aguacortetipodebito_x45_codcortetipodebito_seq do campo: x45_codcortetipodebito"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->x45_codcortetipodebito = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from aguacortetipodebito_x45_codcortetipodebito_seq");
       if(($result != false) && (pg_result($result,0,0) < $x45_codcortetipodebito)){
         $this->erro_sql = " Campo x45_codcortetipodebito maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->x45_codcortetipodebito = $x45_codcortetipodebito; 
       }
     }
     if(($this->x45_codcortetipodebito == null) || ($this->x45_codcortetipodebito == "") ){ 
       $this->erro_sql = " Campo x45_codcortetipodebito nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aguacortetipodebito(
                                       x45_codcorte 
                                      ,x45_tipo 
                                      ,x45_parcelas 
                                      ,x45_dtvenc 
                                      ,x45_vlrminimo 
                                      ,x45_codcortetipodebito 
                                      ,x45_dtopini 
                                      ,x45_dtopfim 
                       )
                values (
                                $this->x45_codcorte 
                               ,$this->x45_tipo 
                               ,$this->x45_parcelas 
                               ,".($this->x45_dtvenc == "null" || $this->x45_dtvenc == ""?"null":"'".$this->x45_dtvenc."'")." 
                               ,$this->x45_vlrminimo 
                               ,$this->x45_codcortetipodebito 
                               ,".($this->x45_dtopini == "null" || $this->x45_dtopini == ""?"null":"'".$this->x45_dtopini."'")." 
                               ,".($this->x45_dtopfim == "null" || $this->x45_dtopfim == ""?"null":"'".$this->x45_dtopfim."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "aguacortetipodebito ($this->x45_codcortetipodebito) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "aguacortetipodebito já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "aguacortetipodebito ($this->x45_codcortetipodebito) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x45_codcortetipodebito;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->x45_codcortetipodebito));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8568,'$this->x45_codcortetipodebito','I')");
       $resac = db_query("insert into db_acount values($acount,1453,8539,'','".AddSlashes(pg_result($resaco,0,'x45_codcorte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1453,8540,'','".AddSlashes(pg_result($resaco,0,'x45_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1453,8541,'','".AddSlashes(pg_result($resaco,0,'x45_parcelas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1453,8542,'','".AddSlashes(pg_result($resaco,0,'x45_dtvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1453,8543,'','".AddSlashes(pg_result($resaco,0,'x45_vlrminimo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1453,8568,'','".AddSlashes(pg_result($resaco,0,'x45_codcortetipodebito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1453,8571,'','".AddSlashes(pg_result($resaco,0,'x45_dtopini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1453,8572,'','".AddSlashes(pg_result($resaco,0,'x45_dtopfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($x45_codcortetipodebito=null) { 
      $this->atualizacampos();
     $sql = " update aguacortetipodebito set ";
     $virgula = "";
     if(trim($this->x45_codcorte)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x45_codcorte"])){ 
       $sql  .= $virgula." x45_codcorte = $this->x45_codcorte ";
       $virgula = ",";
       if(trim($this->x45_codcorte) == null ){ 
         $this->erro_sql = " Campo Corte nao Informado.";
         $this->erro_campo = "x45_codcorte";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x45_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x45_tipo"])){ 
       $sql  .= $virgula." x45_tipo = $this->x45_tipo ";
       $virgula = ",";
       if(trim($this->x45_tipo) == null ){ 
         $this->erro_sql = " Campo Débito nao Informado.";
         $this->erro_campo = "x45_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x45_parcelas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x45_parcelas"])){ 
       $sql  .= $virgula." x45_parcelas = $this->x45_parcelas ";
       $virgula = ",";
       if(trim($this->x45_parcelas) == null ){ 
         $this->erro_sql = " Campo Parcelas Atraso nao Informado.";
         $this->erro_campo = "x45_parcelas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x45_dtvenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x45_dtvenc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["x45_dtvenc_dia"] !="") ){ 
       $sql  .= $virgula." x45_dtvenc = '$this->x45_dtvenc' ";
       $virgula = ",";
       if(trim($this->x45_dtvenc) == null ){ 
         $this->erro_sql = " Campo Vencimento nao Informado.";
         $this->erro_campo = "x45_dtvenc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["x45_dtvenc_dia"])){ 
         $sql  .= $virgula." x45_dtvenc = null ";
         $virgula = ",";
         if(trim($this->x45_dtvenc) == null ){ 
           $this->erro_sql = " Campo Vencimento nao Informado.";
           $this->erro_campo = "x45_dtvenc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->x45_vlrminimo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x45_vlrminimo"])){ 
        if(trim($this->x45_vlrminimo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x45_vlrminimo"])){ 
           $this->x45_vlrminimo = "0" ; 
        } 
       $sql  .= $virgula." x45_vlrminimo = $this->x45_vlrminimo ";
       $virgula = ",";
     }
     if(trim($this->x45_codcortetipodebito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x45_codcortetipodebito"])){ 
       $sql  .= $virgula." x45_codcortetipodebito = $this->x45_codcortetipodebito ";
       $virgula = ",";
       if(trim($this->x45_codcortetipodebito) == null ){ 
         $this->erro_sql = " Campo Corte Tipo Debito nao Informado.";
         $this->erro_campo = "x45_codcortetipodebito";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x45_dtopini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x45_dtopini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["x45_dtopini_dia"] !="") ){ 
       $sql  .= $virgula." x45_dtopini = '$this->x45_dtopini' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["x45_dtopini_dia"])){ 
         $sql  .= $virgula." x45_dtopini = null ";
         $virgula = ",";
       }
     }
     if(trim($this->x45_dtopfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x45_dtopfim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["x45_dtopfim_dia"] !="") ){ 
       $sql  .= $virgula." x45_dtopfim = '$this->x45_dtopfim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["x45_dtopfim_dia"])){ 
         $sql  .= $virgula." x45_dtopfim = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($x45_codcortetipodebito!=null){
       $sql .= " x45_codcortetipodebito = $this->x45_codcortetipodebito";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->x45_codcortetipodebito));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8568,'$this->x45_codcortetipodebito','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x45_codcorte"]))
           $resac = db_query("insert into db_acount values($acount,1453,8539,'".AddSlashes(pg_result($resaco,$conresaco,'x45_codcorte'))."','$this->x45_codcorte',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x45_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1453,8540,'".AddSlashes(pg_result($resaco,$conresaco,'x45_tipo'))."','$this->x45_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x45_parcelas"]))
           $resac = db_query("insert into db_acount values($acount,1453,8541,'".AddSlashes(pg_result($resaco,$conresaco,'x45_parcelas'))."','$this->x45_parcelas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x45_dtvenc"]))
           $resac = db_query("insert into db_acount values($acount,1453,8542,'".AddSlashes(pg_result($resaco,$conresaco,'x45_dtvenc'))."','$this->x45_dtvenc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x45_vlrminimo"]))
           $resac = db_query("insert into db_acount values($acount,1453,8543,'".AddSlashes(pg_result($resaco,$conresaco,'x45_vlrminimo'))."','$this->x45_vlrminimo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x45_codcortetipodebito"]))
           $resac = db_query("insert into db_acount values($acount,1453,8568,'".AddSlashes(pg_result($resaco,$conresaco,'x45_codcortetipodebito'))."','$this->x45_codcortetipodebito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x45_dtopini"]))
           $resac = db_query("insert into db_acount values($acount,1453,8571,'".AddSlashes(pg_result($resaco,$conresaco,'x45_dtopini'))."','$this->x45_dtopini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x45_dtopfim"]))
           $resac = db_query("insert into db_acount values($acount,1453,8572,'".AddSlashes(pg_result($resaco,$conresaco,'x45_dtopfim'))."','$this->x45_dtopfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguacortetipodebito nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->x45_codcortetipodebito;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguacortetipodebito nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->x45_codcortetipodebito;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x45_codcortetipodebito;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($x45_codcortetipodebito=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($x45_codcortetipodebito));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8568,'$x45_codcortetipodebito','E')");
         $resac = db_query("insert into db_acount values($acount,1453,8539,'','".AddSlashes(pg_result($resaco,$iresaco,'x45_codcorte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1453,8540,'','".AddSlashes(pg_result($resaco,$iresaco,'x45_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1453,8541,'','".AddSlashes(pg_result($resaco,$iresaco,'x45_parcelas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1453,8542,'','".AddSlashes(pg_result($resaco,$iresaco,'x45_dtvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1453,8543,'','".AddSlashes(pg_result($resaco,$iresaco,'x45_vlrminimo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1453,8568,'','".AddSlashes(pg_result($resaco,$iresaco,'x45_codcortetipodebito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1453,8571,'','".AddSlashes(pg_result($resaco,$iresaco,'x45_dtopini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1453,8572,'','".AddSlashes(pg_result($resaco,$iresaco,'x45_dtopfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from aguacortetipodebito
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($x45_codcortetipodebito != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " x45_codcortetipodebito = $x45_codcortetipodebito ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguacortetipodebito nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$x45_codcortetipodebito;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguacortetipodebito nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$x45_codcortetipodebito;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$x45_codcortetipodebito;
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
        $this->erro_sql   = "Record Vazio na Tabela:aguacortetipodebito";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $x45_codcortetipodebito=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguacortetipodebito ";
     $sql .= "      inner join arretipo  on  arretipo.k00_tipo = aguacortetipodebito.x45_tipo";
     $sql .= "      inner join aguacorte  on  aguacorte.x40_codcorte = aguacortetipodebito.x45_codcorte";
     $sql .= "      inner join cadtipo  on  cadtipo.k03_tipo = arretipo.k03_tipo";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = aguacorte.x40_rua";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = aguacorte.x40_usuario";
     $sql .= "      inner join aguacortesituacao  on  aguacortesituacao.x43_codsituacao = aguacorte.x40_codsituacao";
     $sql2 = "";
     if($dbwhere==""){
       if($x45_codcortetipodebito!=null ){
         $sql2 .= " where aguacortetipodebito.x45_codcortetipodebito = $x45_codcortetipodebito "; 
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
   function sql_query_file ( $x45_codcortetipodebito=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguacortetipodebito ";
     $sql2 = "";
     if($dbwhere==""){
       if($x45_codcortetipodebito!=null ){
         $sql2 .= " where aguacortetipodebito.x45_codcortetipodebito = $x45_codcortetipodebito "; 
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