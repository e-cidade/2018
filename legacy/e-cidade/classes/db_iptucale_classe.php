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

//MODULO: cadastro
//CLASSE DA ENTIDADE iptucale
class cl_iptucale { 
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
   var $j22_anousu = 0; 
   var $j22_matric = 0; 
   var $j22_idcons = 0; 
   var $j22_areaed = 0; 
   var $j22_vm2 = 0; 
   var $j22_pontos = 0; 
   var $j22_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j22_anousu = int4 = Exercicio 
                 j22_matric = int4 = Matricula 
                 j22_idcons = int4 = Construcao 
                 j22_areaed = float8 = Area Construida 
                 j22_vm2 = float8 = Valor M2 Construcao 
                 j22_pontos = int4 = Pontuacao 
                 j22_valor = float8 = Valor venal 
                 ";
   //funcao construtor da classe 
   function cl_iptucale() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("iptucale"); 
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
       $this->j22_anousu = ($this->j22_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["j22_anousu"]:$this->j22_anousu);
       $this->j22_matric = ($this->j22_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j22_matric"]:$this->j22_matric);
       $this->j22_idcons = ($this->j22_idcons == ""?@$GLOBALS["HTTP_POST_VARS"]["j22_idcons"]:$this->j22_idcons);
       $this->j22_areaed = ($this->j22_areaed == ""?@$GLOBALS["HTTP_POST_VARS"]["j22_areaed"]:$this->j22_areaed);
       $this->j22_vm2 = ($this->j22_vm2 == ""?@$GLOBALS["HTTP_POST_VARS"]["j22_vm2"]:$this->j22_vm2);
       $this->j22_pontos = ($this->j22_pontos == ""?@$GLOBALS["HTTP_POST_VARS"]["j22_pontos"]:$this->j22_pontos);
       $this->j22_valor = ($this->j22_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["j22_valor"]:$this->j22_valor);
     }else{
       $this->j22_anousu = ($this->j22_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["j22_anousu"]:$this->j22_anousu);
       $this->j22_matric = ($this->j22_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j22_matric"]:$this->j22_matric);
       $this->j22_idcons = ($this->j22_idcons == ""?@$GLOBALS["HTTP_POST_VARS"]["j22_idcons"]:$this->j22_idcons);
     }
   }
   // funcao para inclusao
   function incluir ($j22_anousu,$j22_matric,$j22_idcons){ 
      $this->atualizacampos();
     if($this->j22_areaed == null ){ 
       $this->erro_sql = " Campo Area Construida nao Informado.";
       $this->erro_campo = "j22_areaed";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j22_vm2 == null ){ 
       $this->erro_sql = " Campo Valor M2 Construcao nao Informado.";
       $this->erro_campo = "j22_vm2";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j22_pontos == null ){ 
       $this->erro_sql = " Campo Pontuacao nao Informado.";
       $this->erro_campo = "j22_pontos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j22_valor == null ){ 
       $this->erro_sql = " Campo Valor venal nao Informado.";
       $this->erro_campo = "j22_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->j22_anousu = $j22_anousu; 
       $this->j22_matric = $j22_matric; 
       $this->j22_idcons = $j22_idcons; 
     if(($this->j22_anousu == null) || ($this->j22_anousu == "") ){ 
       $this->erro_sql = " Campo j22_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->j22_matric == null) || ($this->j22_matric == "") ){ 
       $this->erro_sql = " Campo j22_matric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->j22_idcons == null) || ($this->j22_idcons == "") ){ 
       $this->erro_sql = " Campo j22_idcons nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into iptucale(
                                       j22_anousu 
                                      ,j22_matric 
                                      ,j22_idcons 
                                      ,j22_areaed 
                                      ,j22_vm2 
                                      ,j22_pontos 
                                      ,j22_valor 
                       )
                values (
                                $this->j22_anousu 
                               ,$this->j22_matric 
                               ,$this->j22_idcons 
                               ,$this->j22_areaed 
                               ,$this->j22_vm2 
                               ,$this->j22_pontos 
                               ,$this->j22_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->j22_anousu."-".$this->j22_matric."-".$this->j22_idcons) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->j22_anousu."-".$this->j22_matric."-".$this->j22_idcons) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j22_anousu."-".$this->j22_matric."-".$this->j22_idcons;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j22_anousu,$this->j22_matric,$this->j22_idcons));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,661,'$this->j22_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,662,'$this->j22_matric','I')");
       $resac = db_query("insert into db_acountkey values($acount,663,'$this->j22_idcons','I')");
       $resac = db_query("insert into db_acount values($acount,123,661,'','".AddSlashes(pg_result($resaco,0,'j22_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,123,662,'','".AddSlashes(pg_result($resaco,0,'j22_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,123,663,'','".AddSlashes(pg_result($resaco,0,'j22_idcons'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,123,664,'','".AddSlashes(pg_result($resaco,0,'j22_areaed'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,123,665,'','".AddSlashes(pg_result($resaco,0,'j22_vm2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,123,666,'','".AddSlashes(pg_result($resaco,0,'j22_pontos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,123,667,'','".AddSlashes(pg_result($resaco,0,'j22_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j22_anousu=null,$j22_matric=null,$j22_idcons=null) { 
      $this->atualizacampos();
     $sql = " update iptucale set ";
     $virgula = "";
     if(trim($this->j22_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j22_anousu"])){ 
       $sql  .= $virgula." j22_anousu = $this->j22_anousu ";
       $virgula = ",";
       if(trim($this->j22_anousu) == null ){ 
         $this->erro_sql = " Campo Exercicio nao Informado.";
         $this->erro_campo = "j22_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j22_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j22_matric"])){ 
       $sql  .= $virgula." j22_matric = $this->j22_matric ";
       $virgula = ",";
       if(trim($this->j22_matric) == null ){ 
         $this->erro_sql = " Campo Matricula nao Informado.";
         $this->erro_campo = "j22_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j22_idcons)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j22_idcons"])){ 
       $sql  .= $virgula." j22_idcons = $this->j22_idcons ";
       $virgula = ",";
       if(trim($this->j22_idcons) == null ){ 
         $this->erro_sql = " Campo Construcao nao Informado.";
         $this->erro_campo = "j22_idcons";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j22_areaed)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j22_areaed"])){ 
       $sql  .= $virgula." j22_areaed = $this->j22_areaed ";
       $virgula = ",";
       if(trim($this->j22_areaed) == null ){ 
         $this->erro_sql = " Campo Area Construida nao Informado.";
         $this->erro_campo = "j22_areaed";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j22_vm2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j22_vm2"])){ 
       $sql  .= $virgula." j22_vm2 = $this->j22_vm2 ";
       $virgula = ",";
       if(trim($this->j22_vm2) == null ){ 
         $this->erro_sql = " Campo Valor M2 Construcao nao Informado.";
         $this->erro_campo = "j22_vm2";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j22_pontos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j22_pontos"])){ 
       $sql  .= $virgula." j22_pontos = $this->j22_pontos ";
       $virgula = ",";
       if(trim($this->j22_pontos) == null ){ 
         $this->erro_sql = " Campo Pontuacao nao Informado.";
         $this->erro_campo = "j22_pontos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j22_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j22_valor"])){ 
       $sql  .= $virgula." j22_valor = $this->j22_valor ";
       $virgula = ",";
       if(trim($this->j22_valor) == null ){ 
         $this->erro_sql = " Campo Valor venal nao Informado.";
         $this->erro_campo = "j22_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j22_anousu!=null){
       $sql .= " j22_anousu = $this->j22_anousu";
     }
     if($j22_matric!=null){
       $sql .= " and  j22_matric = $this->j22_matric";
     }
     if($j22_idcons!=null){
       $sql .= " and  j22_idcons = $this->j22_idcons";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j22_anousu,$this->j22_matric,$this->j22_idcons));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,661,'$this->j22_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,662,'$this->j22_matric','A')");
         $resac = db_query("insert into db_acountkey values($acount,663,'$this->j22_idcons','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j22_anousu"]))
           $resac = db_query("insert into db_acount values($acount,123,661,'".AddSlashes(pg_result($resaco,$conresaco,'j22_anousu'))."','$this->j22_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j22_matric"]))
           $resac = db_query("insert into db_acount values($acount,123,662,'".AddSlashes(pg_result($resaco,$conresaco,'j22_matric'))."','$this->j22_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j22_idcons"]))
           $resac = db_query("insert into db_acount values($acount,123,663,'".AddSlashes(pg_result($resaco,$conresaco,'j22_idcons'))."','$this->j22_idcons',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j22_areaed"]))
           $resac = db_query("insert into db_acount values($acount,123,664,'".AddSlashes(pg_result($resaco,$conresaco,'j22_areaed'))."','$this->j22_areaed',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j22_vm2"]))
           $resac = db_query("insert into db_acount values($acount,123,665,'".AddSlashes(pg_result($resaco,$conresaco,'j22_vm2'))."','$this->j22_vm2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j22_pontos"]))
           $resac = db_query("insert into db_acount values($acount,123,666,'".AddSlashes(pg_result($resaco,$conresaco,'j22_pontos'))."','$this->j22_pontos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j22_valor"]))
           $resac = db_query("insert into db_acount values($acount,123,667,'".AddSlashes(pg_result($resaco,$conresaco,'j22_valor'))."','$this->j22_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j22_anousu."-".$this->j22_matric."-".$this->j22_idcons;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j22_anousu."-".$this->j22_matric."-".$this->j22_idcons;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j22_anousu."-".$this->j22_matric."-".$this->j22_idcons;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j22_anousu=null,$j22_matric=null,$j22_idcons=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j22_anousu,$j22_matric,$j22_idcons));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,661,'$j22_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,662,'$j22_matric','E')");
         $resac = db_query("insert into db_acountkey values($acount,663,'$j22_idcons','E')");
         $resac = db_query("insert into db_acount values($acount,123,661,'','".AddSlashes(pg_result($resaco,$iresaco,'j22_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,123,662,'','".AddSlashes(pg_result($resaco,$iresaco,'j22_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,123,663,'','".AddSlashes(pg_result($resaco,$iresaco,'j22_idcons'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,123,664,'','".AddSlashes(pg_result($resaco,$iresaco,'j22_areaed'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,123,665,'','".AddSlashes(pg_result($resaco,$iresaco,'j22_vm2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,123,666,'','".AddSlashes(pg_result($resaco,$iresaco,'j22_pontos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,123,667,'','".AddSlashes(pg_result($resaco,$iresaco,'j22_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from iptucale
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j22_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j22_anousu = $j22_anousu ";
        }
        if($j22_matric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j22_matric = $j22_matric ";
        }
        if($j22_idcons != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j22_idcons = $j22_idcons ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j22_anousu."-".$j22_matric."-".$j22_idcons;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j22_anousu."-".$j22_matric."-".$j22_idcons;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j22_anousu."-".$j22_matric."-".$j22_idcons;
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
        $this->erro_sql   = "Record Vazio na Tabela:iptucale";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $j22_anousu=null,$j22_matric=null,$j22_idcons=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptucale ";
     $sql .= "      inner join iptuconstr  on  iptuconstr.j39_matric = iptucale.j22_matric and  iptuconstr.j39_idcons = iptucale.j22_idcons";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = iptuconstr.j39_codigo";
     $sql .= "      inner join iptubase  on  iptubase.j01_matric = iptuconstr.j39_matric";
     $sql .= "      inner join ruas  as a on   a.j14_codigo = iptuconstr.j39_codigo";
     $sql .= "      inner join iptubase  as b on   b.j01_matric = iptuconstr.j39_matric";
     $sql2 = "";
     if($dbwhere==""){
       if($j22_anousu!=null ){
         $sql2 .= " where iptucale.j22_anousu = $j22_anousu "; 
       } 
       if($j22_matric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " iptucale.j22_matric = $j22_matric "; 
       } 
       if($j22_idcons!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " iptucale.j22_idcons = $j22_idcons "; 
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
   function sql_query_file ( $j22_anousu=null,$j22_matric=null,$j22_idcons=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptucale ";
     $sql2 = "";
     if($dbwhere==""){
       if($j22_anousu!=null ){
         $sql2 .= " where iptucale.j22_anousu = $j22_anousu "; 
       } 
       if($j22_matric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " iptucale.j22_matric = $j22_matric "; 
       } 
       if($j22_idcons!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " iptucale.j22_idcons = $j22_idcons "; 
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