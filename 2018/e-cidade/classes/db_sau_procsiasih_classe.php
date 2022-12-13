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

//MODULO: saude
//CLASSE DA ENTIDADE sau_procsiasih
class cl_sau_procsiasih { 
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
   var $sd94_i_codigo = 0; 
   var $sd94_i_procedimento = 0; 
   var $sd94_i_siasih = 0; 
   var $sd94_i_tipoproc = 0; 
   var $sd94_i_anocomp = 0; 
   var $sd94_i_mescomp = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd94_i_codigo = int8 = C�digo 
                 sd94_i_procedimento = int8 = Procedimento 
                 sd94_i_siasih = int8 = SIA SIH 
                 sd94_i_tipoproc = int8 = Tipo de processo 
                 sd94_i_anocomp = int4 = Ano 
                 sd94_i_mescomp = int4 = Mes 
                 ";
   //funcao construtor da classe 
   function cl_sau_procsiasih() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_procsiasih"); 
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
       $this->sd94_i_codigo = ($this->sd94_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd94_i_codigo"]:$this->sd94_i_codigo);
       $this->sd94_i_procedimento = ($this->sd94_i_procedimento == ""?@$GLOBALS["HTTP_POST_VARS"]["sd94_i_procedimento"]:$this->sd94_i_procedimento);
       $this->sd94_i_siasih = ($this->sd94_i_siasih == ""?@$GLOBALS["HTTP_POST_VARS"]["sd94_i_siasih"]:$this->sd94_i_siasih);
       $this->sd94_i_tipoproc = ($this->sd94_i_tipoproc == ""?@$GLOBALS["HTTP_POST_VARS"]["sd94_i_tipoproc"]:$this->sd94_i_tipoproc);
       $this->sd94_i_anocomp = ($this->sd94_i_anocomp == ""?@$GLOBALS["HTTP_POST_VARS"]["sd94_i_anocomp"]:$this->sd94_i_anocomp);
       $this->sd94_i_mescomp = ($this->sd94_i_mescomp == ""?@$GLOBALS["HTTP_POST_VARS"]["sd94_i_mescomp"]:$this->sd94_i_mescomp);
     }else{
       $this->sd94_i_codigo = ($this->sd94_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd94_i_codigo"]:$this->sd94_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd94_i_codigo){ 
      $this->atualizacampos();
     if($this->sd94_i_procedimento == null ){ 
       $this->erro_sql = " Campo Procedimento nao Informado.";
       $this->erro_campo = "sd94_i_procedimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd94_i_siasih == null ){ 
       $this->erro_sql = " Campo SIA SIH nao Informado.";
       $this->erro_campo = "sd94_i_siasih";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd94_i_tipoproc == null ){ 
       $this->erro_sql = " Campo Tipo de processo nao Informado.";
       $this->erro_campo = "sd94_i_tipoproc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd94_i_anocomp == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "sd94_i_anocomp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd94_i_mescomp == null ){ 
       $this->erro_sql = " Campo Mes nao Informado.";
       $this->erro_campo = "sd94_i_mescomp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sd94_i_codigo == "" || $sd94_i_codigo == null ){
       $result = db_query("select nextval('sau_procsiasih_sd94_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sau_procsiasih_sd94_i_codigo_seq do campo: sd94_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->sd94_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from sau_procsiasih_sd94_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd94_i_codigo)){
         $this->erro_sql = " Campo sd94_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd94_i_codigo = $sd94_i_codigo; 
       }
     }
     if(($this->sd94_i_codigo == null) || ($this->sd94_i_codigo == "") ){ 
       $this->erro_sql = " Campo sd94_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_procsiasih(
                                       sd94_i_codigo 
                                      ,sd94_i_procedimento 
                                      ,sd94_i_siasih 
                                      ,sd94_i_tipoproc 
                                      ,sd94_i_anocomp 
                                      ,sd94_i_mescomp 
                       )
                values (
                                $this->sd94_i_codigo 
                               ,$this->sd94_i_procedimento 
                               ,$this->sd94_i_siasih 
                               ,$this->sd94_i_tipoproc 
                               ,$this->sd94_i_anocomp 
                               ,$this->sd94_i_mescomp 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Procedimentos e Sia-Sih ($this->sd94_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Procedimentos e Sia-Sih j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Procedimentos e Sia-Sih ($this->sd94_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd94_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd94_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11616,'$this->sd94_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1995,11616,'','".AddSlashes(pg_result($resaco,0,'sd94_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1995,11617,'','".AddSlashes(pg_result($resaco,0,'sd94_i_procedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1995,11618,'','".AddSlashes(pg_result($resaco,0,'sd94_i_siasih'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1995,11619,'','".AddSlashes(pg_result($resaco,0,'sd94_i_tipoproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1995,11702,'','".AddSlashes(pg_result($resaco,0,'sd94_i_anocomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1995,11703,'','".AddSlashes(pg_result($resaco,0,'sd94_i_mescomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($sd94_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update sau_procsiasih set ";
     $virgula = "";
     if(trim($this->sd94_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd94_i_codigo"])){ 
       $sql  .= $virgula." sd94_i_codigo = $this->sd94_i_codigo ";
       $virgula = ",";
       if(trim($this->sd94_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "sd94_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd94_i_procedimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd94_i_procedimento"])){ 
       $sql  .= $virgula." sd94_i_procedimento = $this->sd94_i_procedimento ";
       $virgula = ",";
       if(trim($this->sd94_i_procedimento) == null ){ 
         $this->erro_sql = " Campo Procedimento nao Informado.";
         $this->erro_campo = "sd94_i_procedimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd94_i_siasih)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd94_i_siasih"])){ 
       $sql  .= $virgula." sd94_i_siasih = $this->sd94_i_siasih ";
       $virgula = ",";
       if(trim($this->sd94_i_siasih) == null ){ 
         $this->erro_sql = " Campo SIA SIH nao Informado.";
         $this->erro_campo = "sd94_i_siasih";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd94_i_tipoproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd94_i_tipoproc"])){ 
       $sql  .= $virgula." sd94_i_tipoproc = $this->sd94_i_tipoproc ";
       $virgula = ",";
       if(trim($this->sd94_i_tipoproc) == null ){ 
         $this->erro_sql = " Campo Tipo de processo nao Informado.";
         $this->erro_campo = "sd94_i_tipoproc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd94_i_anocomp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd94_i_anocomp"])){ 
       $sql  .= $virgula." sd94_i_anocomp = $this->sd94_i_anocomp ";
       $virgula = ",";
       if(trim($this->sd94_i_anocomp) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "sd94_i_anocomp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd94_i_mescomp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd94_i_mescomp"])){ 
       $sql  .= $virgula." sd94_i_mescomp = $this->sd94_i_mescomp ";
       $virgula = ",";
       if(trim($this->sd94_i_mescomp) == null ){ 
         $this->erro_sql = " Campo Mes nao Informado.";
         $this->erro_campo = "sd94_i_mescomp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($sd94_i_codigo!=null){
       $sql .= " sd94_i_codigo = $this->sd94_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd94_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11616,'$this->sd94_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd94_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1995,11616,'".AddSlashes(pg_result($resaco,$conresaco,'sd94_i_codigo'))."','$this->sd94_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd94_i_procedimento"]))
           $resac = db_query("insert into db_acount values($acount,1995,11617,'".AddSlashes(pg_result($resaco,$conresaco,'sd94_i_procedimento'))."','$this->sd94_i_procedimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd94_i_siasih"]))
           $resac = db_query("insert into db_acount values($acount,1995,11618,'".AddSlashes(pg_result($resaco,$conresaco,'sd94_i_siasih'))."','$this->sd94_i_siasih',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd94_i_tipoproc"]))
           $resac = db_query("insert into db_acount values($acount,1995,11619,'".AddSlashes(pg_result($resaco,$conresaco,'sd94_i_tipoproc'))."','$this->sd94_i_tipoproc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd94_i_anocomp"]))
           $resac = db_query("insert into db_acount values($acount,1995,11702,'".AddSlashes(pg_result($resaco,$conresaco,'sd94_i_anocomp'))."','$this->sd94_i_anocomp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd94_i_mescomp"]))
           $resac = db_query("insert into db_acount values($acount,1995,11703,'".AddSlashes(pg_result($resaco,$conresaco,'sd94_i_mescomp'))."','$this->sd94_i_mescomp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Procedimentos e Sia-Sih nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd94_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Procedimentos e Sia-Sih nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd94_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd94_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($sd94_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd94_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11616,'$sd94_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1995,11616,'','".AddSlashes(pg_result($resaco,$iresaco,'sd94_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1995,11617,'','".AddSlashes(pg_result($resaco,$iresaco,'sd94_i_procedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1995,11618,'','".AddSlashes(pg_result($resaco,$iresaco,'sd94_i_siasih'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1995,11619,'','".AddSlashes(pg_result($resaco,$iresaco,'sd94_i_tipoproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1995,11702,'','".AddSlashes(pg_result($resaco,$iresaco,'sd94_i_anocomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1995,11703,'','".AddSlashes(pg_result($resaco,$iresaco,'sd94_i_mescomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sau_procsiasih
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd94_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd94_i_codigo = $sd94_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Procedimentos e Sia-Sih nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd94_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Procedimentos e Sia-Sih nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd94_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd94_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:sau_procsiasih";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $sd94_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from sau_procsiasih ";
     $sql .= "      inner join sau_procedimento  on  sau_procedimento.sd63_i_codigo = sau_procsiasih.sd94_i_procedimento";
     $sql .= "      inner join sau_tipoproc      on  sau_tipoproc.sd93_i_codigo = sau_procsiasih.sd94_i_tipoproc";
     $sql .= "      inner join sau_siasih        on  sau_siasih.sd92_i_codigo = sau_procsiasih.sd94_i_siasih";
     $sql .= "      left join sau_financiamento  on  sau_financiamento.sd65_i_codigo = sau_procedimento.sd63_i_financiamento";
     $sql .= "      left join sau_rubrica        on  sau_rubrica.sd64_i_codigo = sau_procedimento.sd63_i_rubrica";
     $sql .= "      left join sau_complexidade  on  sau_complexidade.sd69_i_codigo = sau_procedimento.sd63_i_complexidade";
     $sql2 = "";
     if($dbwhere==""){
       if($sd94_i_codigo!=null ){
         $sql2 .= " where sau_procsiasih.sd94_i_codigo = $sd94_i_codigo ";
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
   function sql_query_file ( $sd94_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from sau_procsiasih ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd94_i_codigo!=null ){
         $sql2 .= " where sau_procsiasih.sd94_i_codigo = $sd94_i_codigo ";
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