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

//MODULO: projetos
//CLASSE DA ENTIDADE obrasender
class cl_obrasender { 
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
   var $ob07_codconstr = 0; 
   var $ob07_codobra = 0; 
   var $ob07_lograd = 0; 
   var $ob07_numero = 0; 
   var $ob07_compl = null; 
   var $ob07_bairro = 0; 
   var $ob07_areaatual = 0; 
   var $ob07_unidades = 0; 
   var $ob07_pavimentos = 0; 
   var $ob07_inicio_dia = null; 
   var $ob07_inicio_mes = null; 
   var $ob07_inicio_ano = null; 
   var $ob07_inicio = null; 
   var $ob07_fim_dia = null; 
   var $ob07_fim_mes = null; 
   var $ob07_fim_ano = null; 
   var $ob07_fim = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ob07_codconstr = int4 = Código da construção 
                 ob07_codobra = int4 = Código da obra 
                 ob07_lograd = int4 = cód. Rua/Avenida 
                 ob07_numero = int4 = Número 
                 ob07_compl = varchar(20) = Complemento 
                 ob07_bairro = int4 = Bairro 
                 ob07_areaatual = float8 = Área atual 
                 ob07_unidades = int4 = Unidades 
                 ob07_pavimentos = int4 = Pavimentos 
                 ob07_inicio = date = Data inicio 
                 ob07_fim = date = Data final 
                 ";
   //funcao construtor da classe 
   function cl_obrasender() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("obrasender"); 
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
       $this->ob07_codconstr = ($this->ob07_codconstr == ""?@$GLOBALS["HTTP_POST_VARS"]["ob07_codconstr"]:$this->ob07_codconstr);
       $this->ob07_codobra = ($this->ob07_codobra == ""?@$GLOBALS["HTTP_POST_VARS"]["ob07_codobra"]:$this->ob07_codobra);
       $this->ob07_lograd = ($this->ob07_lograd == ""?@$GLOBALS["HTTP_POST_VARS"]["ob07_lograd"]:$this->ob07_lograd);
       $this->ob07_numero = ($this->ob07_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["ob07_numero"]:$this->ob07_numero);
       $this->ob07_compl = ($this->ob07_compl == ""?@$GLOBALS["HTTP_POST_VARS"]["ob07_compl"]:$this->ob07_compl);
       $this->ob07_bairro = ($this->ob07_bairro == ""?@$GLOBALS["HTTP_POST_VARS"]["ob07_bairro"]:$this->ob07_bairro);
       $this->ob07_areaatual = ($this->ob07_areaatual == ""?@$GLOBALS["HTTP_POST_VARS"]["ob07_areaatual"]:$this->ob07_areaatual);
       $this->ob07_unidades = ($this->ob07_unidades == ""?@$GLOBALS["HTTP_POST_VARS"]["ob07_unidades"]:$this->ob07_unidades);
       $this->ob07_pavimentos = ($this->ob07_pavimentos == ""?@$GLOBALS["HTTP_POST_VARS"]["ob07_pavimentos"]:$this->ob07_pavimentos);
       if($this->ob07_inicio == ""){
         $this->ob07_inicio_dia = ($this->ob07_inicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ob07_inicio_dia"]:$this->ob07_inicio_dia);
         $this->ob07_inicio_mes = ($this->ob07_inicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ob07_inicio_mes"]:$this->ob07_inicio_mes);
         $this->ob07_inicio_ano = ($this->ob07_inicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ob07_inicio_ano"]:$this->ob07_inicio_ano);
         if($this->ob07_inicio_dia != ""){
            $this->ob07_inicio = $this->ob07_inicio_ano."-".$this->ob07_inicio_mes."-".$this->ob07_inicio_dia;
         }
       }
       if($this->ob07_fim == ""){
         $this->ob07_fim_dia = ($this->ob07_fim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ob07_fim_dia"]:$this->ob07_fim_dia);
         $this->ob07_fim_mes = ($this->ob07_fim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ob07_fim_mes"]:$this->ob07_fim_mes);
         $this->ob07_fim_ano = ($this->ob07_fim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ob07_fim_ano"]:$this->ob07_fim_ano);
         if($this->ob07_fim_dia != ""){
            $this->ob07_fim = $this->ob07_fim_ano."-".$this->ob07_fim_mes."-".$this->ob07_fim_dia;
         }
       }
     }else{
       $this->ob07_codconstr = ($this->ob07_codconstr == ""?@$GLOBALS["HTTP_POST_VARS"]["ob07_codconstr"]:$this->ob07_codconstr);
     }
   }
   // funcao para inclusao
   function incluir ($ob07_codconstr){ 
      $this->atualizacampos();
     if($this->ob07_codobra == null ){ 
       $this->erro_sql = " Campo Código da obra nao Informado.";
       $this->erro_campo = "ob07_codobra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob07_lograd == null ){ 
       $this->erro_sql = " Campo cód. Rua/Avenida nao Informado.";
       $this->erro_campo = "ob07_lograd";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob07_numero == null ){ 
       $this->ob07_numero = "0";
     }
     if($this->ob07_bairro == null ){ 
       $this->erro_sql = " Campo Bairro nao Informado.";
       $this->erro_campo = "ob07_bairro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob07_areaatual == null ){ 
       $this->erro_sql = " Campo Área atual nao Informado.";
       $this->erro_campo = "ob07_areaatual";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob07_unidades == null ){ 
       $this->erro_sql = " Campo Unidades nao Informado.";
       $this->erro_campo = "ob07_unidades";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob07_pavimentos == null ){ 
       $this->erro_sql = " Campo Pavimentos nao Informado.";
       $this->erro_campo = "ob07_pavimentos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob07_inicio == null ){ 
       $this->ob07_inicio = "null";
     }
     if($this->ob07_fim == null ){ 
       $this->ob07_fim = "null";
     }
       $this->ob07_codconstr = $ob07_codconstr; 
     if(($this->ob07_codconstr == null) || ($this->ob07_codconstr == "") ){ 
       $this->erro_sql = " Campo ob07_codconstr nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into obrasender(
                                       ob07_codconstr 
                                      ,ob07_codobra 
                                      ,ob07_lograd 
                                      ,ob07_numero 
                                      ,ob07_compl 
                                      ,ob07_bairro 
                                      ,ob07_areaatual 
                                      ,ob07_unidades 
                                      ,ob07_pavimentos 
                                      ,ob07_inicio 
                                      ,ob07_fim 
                       )
                values (
                                $this->ob07_codconstr 
                               ,$this->ob07_codobra 
                               ,$this->ob07_lograd 
                               ,$this->ob07_numero 
                               ,'$this->ob07_compl' 
                               ,$this->ob07_bairro 
                               ,$this->ob07_areaatual 
                               ,$this->ob07_unidades 
                               ,$this->ob07_pavimentos 
                               ,".($this->ob07_inicio == "null" || $this->ob07_inicio == ""?"null":"'".$this->ob07_inicio."'")." 
                               ,".($this->ob07_fim == "null" || $this->ob07_fim == ""?"null":"'".$this->ob07_fim."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "endereço da obra ($this->ob07_codconstr) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "endereço da obra já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "endereço da obra ($this->ob07_codconstr) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ob07_codconstr;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ob07_codconstr));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6010,'$this->ob07_codconstr','I')");
       $resac = db_query("insert into db_acount values($acount,952,6010,'','".AddSlashes(pg_result($resaco,0,'ob07_codconstr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,952,5926,'','".AddSlashes(pg_result($resaco,0,'ob07_codobra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,952,5927,'','".AddSlashes(pg_result($resaco,0,'ob07_lograd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,952,5928,'','".AddSlashes(pg_result($resaco,0,'ob07_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,952,5929,'','".AddSlashes(pg_result($resaco,0,'ob07_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,952,5930,'','".AddSlashes(pg_result($resaco,0,'ob07_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,952,5931,'','".AddSlashes(pg_result($resaco,0,'ob07_areaatual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,952,5932,'','".AddSlashes(pg_result($resaco,0,'ob07_unidades'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,952,5933,'','".AddSlashes(pg_result($resaco,0,'ob07_pavimentos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,952,5934,'','".AddSlashes(pg_result($resaco,0,'ob07_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,952,5935,'','".AddSlashes(pg_result($resaco,0,'ob07_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ob07_codconstr=null) { 
      $this->atualizacampos();
     $sql = " update obrasender set ";
     $virgula = "";
     if(trim($this->ob07_codconstr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob07_codconstr"])){ 
       $sql  .= $virgula." ob07_codconstr = $this->ob07_codconstr ";
       $virgula = ",";
       if(trim($this->ob07_codconstr) == null ){ 
         $this->erro_sql = " Campo Código da construção nao Informado.";
         $this->erro_campo = "ob07_codconstr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob07_codobra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob07_codobra"])){ 
       $sql  .= $virgula." ob07_codobra = $this->ob07_codobra ";
       $virgula = ",";
       if(trim($this->ob07_codobra) == null ){ 
         $this->erro_sql = " Campo Código da obra nao Informado.";
         $this->erro_campo = "ob07_codobra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob07_lograd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob07_lograd"])){ 
       $sql  .= $virgula." ob07_lograd = $this->ob07_lograd ";
       $virgula = ",";
       if(trim($this->ob07_lograd) == null ){ 
         $this->erro_sql = " Campo cód. Rua/Avenida nao Informado.";
         $this->erro_campo = "ob07_lograd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob07_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob07_numero"])){ 
        if(trim($this->ob07_numero)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ob07_numero"])){ 
           $this->ob07_numero = "0" ; 
        } 
       $sql  .= $virgula." ob07_numero = $this->ob07_numero ";
       $virgula = ",";
     }
     if(trim($this->ob07_compl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob07_compl"])){ 
       $sql  .= $virgula." ob07_compl = '$this->ob07_compl' ";
       $virgula = ",";
     }
     if(trim($this->ob07_bairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob07_bairro"])){ 
       $sql  .= $virgula." ob07_bairro = $this->ob07_bairro ";
       $virgula = ",";
       if(trim($this->ob07_bairro) == null ){ 
         $this->erro_sql = " Campo Bairro nao Informado.";
         $this->erro_campo = "ob07_bairro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob07_areaatual)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob07_areaatual"])){ 
       $sql  .= $virgula." ob07_areaatual = $this->ob07_areaatual ";
       $virgula = ",";
       if(trim($this->ob07_areaatual) == null ){ 
         $this->erro_sql = " Campo Área atual nao Informado.";
         $this->erro_campo = "ob07_areaatual";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob07_unidades)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob07_unidades"])){ 
       $sql  .= $virgula." ob07_unidades = $this->ob07_unidades ";
       $virgula = ",";
       if(trim($this->ob07_unidades) == null ){ 
         $this->erro_sql = " Campo Unidades nao Informado.";
         $this->erro_campo = "ob07_unidades";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob07_pavimentos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob07_pavimentos"])){ 
       $sql  .= $virgula." ob07_pavimentos = $this->ob07_pavimentos ";
       $virgula = ",";
       if(trim($this->ob07_pavimentos) == null ){ 
         $this->erro_sql = " Campo Pavimentos nao Informado.";
         $this->erro_campo = "ob07_pavimentos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob07_inicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob07_inicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ob07_inicio_dia"] !="") ){ 
       $sql  .= $virgula." ob07_inicio = '$this->ob07_inicio' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ob07_inicio_dia"])){ 
         $sql  .= $virgula." ob07_inicio = null ";
         $virgula = ",";
       }
     }
     if(trim($this->ob07_fim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob07_fim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ob07_fim_dia"] !="") ){ 
       $sql  .= $virgula." ob07_fim = '$this->ob07_fim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ob07_fim_dia"])){ 
         $sql  .= $virgula." ob07_fim = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($ob07_codconstr!=null){
       $sql .= " ob07_codconstr = $this->ob07_codconstr";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ob07_codconstr));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6010,'$this->ob07_codconstr','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob07_codconstr"]))
           $resac = db_query("insert into db_acount values($acount,952,6010,'".AddSlashes(pg_result($resaco,$conresaco,'ob07_codconstr'))."','$this->ob07_codconstr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob07_codobra"]))
           $resac = db_query("insert into db_acount values($acount,952,5926,'".AddSlashes(pg_result($resaco,$conresaco,'ob07_codobra'))."','$this->ob07_codobra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob07_lograd"]))
           $resac = db_query("insert into db_acount values($acount,952,5927,'".AddSlashes(pg_result($resaco,$conresaco,'ob07_lograd'))."','$this->ob07_lograd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob07_numero"]))
           $resac = db_query("insert into db_acount values($acount,952,5928,'".AddSlashes(pg_result($resaco,$conresaco,'ob07_numero'))."','$this->ob07_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob07_compl"]))
           $resac = db_query("insert into db_acount values($acount,952,5929,'".AddSlashes(pg_result($resaco,$conresaco,'ob07_compl'))."','$this->ob07_compl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob07_bairro"]))
           $resac = db_query("insert into db_acount values($acount,952,5930,'".AddSlashes(pg_result($resaco,$conresaco,'ob07_bairro'))."','$this->ob07_bairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob07_areaatual"]))
           $resac = db_query("insert into db_acount values($acount,952,5931,'".AddSlashes(pg_result($resaco,$conresaco,'ob07_areaatual'))."','$this->ob07_areaatual',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob07_unidades"]))
           $resac = db_query("insert into db_acount values($acount,952,5932,'".AddSlashes(pg_result($resaco,$conresaco,'ob07_unidades'))."','$this->ob07_unidades',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob07_pavimentos"]))
           $resac = db_query("insert into db_acount values($acount,952,5933,'".AddSlashes(pg_result($resaco,$conresaco,'ob07_pavimentos'))."','$this->ob07_pavimentos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob07_inicio"]))
           $resac = db_query("insert into db_acount values($acount,952,5934,'".AddSlashes(pg_result($resaco,$conresaco,'ob07_inicio'))."','$this->ob07_inicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob07_fim"]))
           $resac = db_query("insert into db_acount values($acount,952,5935,'".AddSlashes(pg_result($resaco,$conresaco,'ob07_fim'))."','$this->ob07_fim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "endereço da obra nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ob07_codconstr;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "endereço da obra nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ob07_codconstr;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ob07_codconstr;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ob07_codconstr=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ob07_codconstr));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6010,'$ob07_codconstr','E')");
         $resac = db_query("insert into db_acount values($acount,952,6010,'','".AddSlashes(pg_result($resaco,$iresaco,'ob07_codconstr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,952,5926,'','".AddSlashes(pg_result($resaco,$iresaco,'ob07_codobra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,952,5927,'','".AddSlashes(pg_result($resaco,$iresaco,'ob07_lograd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,952,5928,'','".AddSlashes(pg_result($resaco,$iresaco,'ob07_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,952,5929,'','".AddSlashes(pg_result($resaco,$iresaco,'ob07_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,952,5930,'','".AddSlashes(pg_result($resaco,$iresaco,'ob07_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,952,5931,'','".AddSlashes(pg_result($resaco,$iresaco,'ob07_areaatual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,952,5932,'','".AddSlashes(pg_result($resaco,$iresaco,'ob07_unidades'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,952,5933,'','".AddSlashes(pg_result($resaco,$iresaco,'ob07_pavimentos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,952,5934,'','".AddSlashes(pg_result($resaco,$iresaco,'ob07_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,952,5935,'','".AddSlashes(pg_result($resaco,$iresaco,'ob07_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from obrasender
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ob07_codconstr != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ob07_codconstr = $ob07_codconstr ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "endereço da obra nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ob07_codconstr;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "endereço da obra nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ob07_codconstr;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ob07_codconstr;
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
        $this->erro_sql   = "Record Vazio na Tabela:obrasender";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ob07_codconstr=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from obrasender ";
     $sql .= "      inner join bairro  on  bairro.j13_codi = obrasender.ob07_bairro";
     $sql .= "      left outer  join ruas  on  ruas.j14_codigo = obrasender.ob07_lograd";
     $sql .= "      inner join obrasconstr  on  obrasconstr.ob08_codconstr = obrasender.ob07_codconstr";
     $sql .= "      inner join caracter  on  caracter.j31_codigo = obrasconstr.ob08_ocupacao";
     $sql .= "      inner join obras  as a on   a.ob01_codobra = obrasconstr.ob08_codobra";
     $sql2 = "";
     if($dbwhere==""){
       if($ob07_codconstr!=null ){
         $sql2 .= " where obrasender.ob07_codconstr = $ob07_codconstr "; 
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
   function sql_query_file ( $ob07_codconstr=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from obrasender ";
     $sql2 = "";
     if($dbwhere==""){
       if($ob07_codconstr!=null ){
         $sql2 .= " where obrasender.ob07_codconstr = $ob07_codconstr "; 
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
	
   function sql_query_constr( $ob07_codconstr=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from obrasender ";
     $sql .= "      inner join bairro  			on bairro.j13_codi 		 = obrasender.ob07_bairro";
     $sql .= "      left outer  join ruas   on ruas.j14_codigo 		 = obrasender.ob07_lograd";
     $sql .= "      inner join obrasconstr  on obrasconstr.ob08_codconstr = obrasender.ob07_codconstr";
     $sql .= "      inner join caracter  	  on caracter.j31_codigo = obrasconstr.ob08_tipoconstr";
     $sql .= "      inner join obras  as a  on a.ob01_codobra 		 = obrasconstr.ob08_codobra";
     $sql2 = "";
     if($dbwhere==""){
       if($ob07_codconstr!=null ){
         $sql2 .= " where obrasender.ob07_codconstr = $ob07_codconstr "; 
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
  
	/**
	 * Método de alteração alternativo para realizar a alter 
	 * @param integer $ob07_codconstr
	 */
  function alterar_alternativo( $ob07_codconstr = null) {

    $this->atualizacampos();
    
    $sql     = " update obrasender set ";
    $virgula = "";
    
    if(trim($this->ob07_codconstr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob07_codconstr"])){
      
      $sql  .= $virgula." ob07_codconstr = $this->ob07_codconstr ";
      $virgula = ",";
      
      if (trim($this->ob07_codconstr) == null ) {
        
        $this->erro_sql = " Campo Código da construção nao Informado.";
        $this->erro_campo = "ob07_codconstr";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->ob07_codobra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob07_codobra"])){
      
      $sql  .= $virgula." ob07_codobra = $this->ob07_codobra ";
      $virgula = ",";
      if(trim($this->ob07_codobra) == null ){
        $this->erro_sql = " Campo Código da obra nao Informado.";
        $this->erro_campo = "ob07_codobra";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->ob07_lograd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob07_lograd"])){
      $sql  .= $virgula." ob07_lograd = $this->ob07_lograd ";
      $virgula = ",";
      if(trim($this->ob07_lograd) == null ){
        $this->erro_sql = " Campo cód. Rua/Avenida nao Informado.";
        $this->erro_campo = "ob07_lograd";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->ob07_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob07_numero"])){
      if(trim($this->ob07_numero)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ob07_numero"])){
        $this->ob07_numero = "0" ;
      }
      $sql  .= $virgula." ob07_numero = $this->ob07_numero ";
      $virgula = ",";
    }
    if(trim($this->ob07_compl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob07_compl"])){
      $sql  .= $virgula." ob07_compl = '$this->ob07_compl' ";
      $virgula = ",";
    }
    if(trim($this->ob07_bairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob07_bairro"])){
      $sql  .= $virgula." ob07_bairro = $this->ob07_bairro ";
      $virgula = ",";
      if(trim($this->ob07_bairro) == null ){
        $this->erro_sql = " Campo Bairro nao Informado.";
        $this->erro_campo = "ob07_bairro";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->ob07_areaatual)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob07_areaatual"])){
      $sql  .= $virgula." ob07_areaatual = $this->ob07_areaatual ";
      $virgula = ",";
      if(trim($this->ob07_areaatual) == null ){
        $this->erro_sql = " Campo Área atual nao Informado.";
        $this->erro_campo = "ob07_areaatual";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->ob07_unidades)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob07_unidades"])){
      $sql  .= $virgula." ob07_unidades = $this->ob07_unidades ";
      $virgula = ",";
      if(trim($this->ob07_unidades) == null ){
        $this->erro_sql = " Campo Unidades nao Informado.";
        $this->erro_campo = "ob07_unidades";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->ob07_pavimentos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob07_pavimentos"])){
      $sql  .= $virgula." ob07_pavimentos = $this->ob07_pavimentos ";
      $virgula = ",";
      if(trim($this->ob07_pavimentos) == null ){
        $this->erro_sql = " Campo Pavimentos nao Informado.";
        $this->erro_campo = "ob07_pavimentos";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->ob07_inicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob07_inicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ob07_inicio_dia"] !="") ){
      $sql  .= $virgula." ob07_inicio = '$this->ob07_inicio' ";
      $virgula = ",";
    } else {
      if(isset($GLOBALS["HTTP_POST_VARS"]["ob07_inicio_dia"])){
        $sql  .= $virgula." ob07_inicio = null ";
        $virgula = ",";
      }
    }
    if(trim($this->ob07_fim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob07_fim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ob07_fim_dia"] !="") ){
      $sql  .= $virgula." ob07_fim = '$this->ob07_fim' ";
      $virgula = ",";
    } else {
      $sql  .= $virgula." ob07_fim = null ";
      $virgula = ",";
    }
    $sql .= " where ";
    if($ob07_codconstr!=null){
      $sql .= " ob07_codconstr = $this->ob07_codconstr";
    }
    $resaco = $this->sql_record($this->sql_query_file($this->ob07_codconstr));
    if($this->numrows>0){
      for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,6010,'$this->ob07_codconstr','A')");
        if(isset($GLOBALS["HTTP_POST_VARS"]["ob07_codconstr"]))
        $resac = db_query("insert into db_acount values($acount,952,6010,'".AddSlashes(pg_result($resaco,$conresaco,'ob07_codconstr'))."','$this->ob07_codconstr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["ob07_codobra"]))
        $resac = db_query("insert into db_acount values($acount,952,5926,'".AddSlashes(pg_result($resaco,$conresaco,'ob07_codobra'))."','$this->ob07_codobra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["ob07_lograd"]))
        $resac = db_query("insert into db_acount values($acount,952,5927,'".AddSlashes(pg_result($resaco,$conresaco,'ob07_lograd'))."','$this->ob07_lograd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["ob07_numero"]))
        $resac = db_query("insert into db_acount values($acount,952,5928,'".AddSlashes(pg_result($resaco,$conresaco,'ob07_numero'))."','$this->ob07_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["ob07_compl"]))
        $resac = db_query("insert into db_acount values($acount,952,5929,'".AddSlashes(pg_result($resaco,$conresaco,'ob07_compl'))."','$this->ob07_compl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["ob07_bairro"]))
        $resac = db_query("insert into db_acount values($acount,952,5930,'".AddSlashes(pg_result($resaco,$conresaco,'ob07_bairro'))."','$this->ob07_bairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["ob07_areaatual"]))
        $resac = db_query("insert into db_acount values($acount,952,5931,'".AddSlashes(pg_result($resaco,$conresaco,'ob07_areaatual'))."','$this->ob07_areaatual',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["ob07_unidades"]))
        $resac = db_query("insert into db_acount values($acount,952,5932,'".AddSlashes(pg_result($resaco,$conresaco,'ob07_unidades'))."','$this->ob07_unidades',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["ob07_pavimentos"]))
        $resac = db_query("insert into db_acount values($acount,952,5933,'".AddSlashes(pg_result($resaco,$conresaco,'ob07_pavimentos'))."','$this->ob07_pavimentos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["ob07_inicio"]))
        $resac = db_query("insert into db_acount values($acount,952,5934,'".AddSlashes(pg_result($resaco,$conresaco,'ob07_inicio'))."','$this->ob07_inicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["ob07_fim"]))
        $resac = db_query("insert into db_acount values($acount,952,5935,'".AddSlashes(pg_result($resaco,$conresaco,'ob07_fim'))."','$this->ob07_fim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "endereço da obra nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores : ".$this->ob07_codconstr;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = "endereço da obra nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql .= "Valores : ".$this->ob07_codconstr;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Alteração efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$this->ob07_codconstr;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }
}
?>