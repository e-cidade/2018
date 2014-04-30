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

//MODULO: protocolo
//CLASSE DA ENTIDADE cgm
class cl_cgm { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $pagina_retorno = null;  
   // cria variaveis do arquivo 
   var $z01_numcgm = 0; 
   var $z01_nome = null; 
   var $z01_ender = null; 
   var $z01_numero = 0; 
   var $z01_compl = null; 
   var $z01_bairro = null; 
   var $z01_munic = null; 
   var $z01_uf = null; 
   var $z01_cep = null; 
   var $z01_cxpostal = null; 
   var $z01_cadast_dia = null; 
   var $z01_cadast_mes = null; 
   var $z01_cadast_ano = null; 
   var $z01_cadast = null; 
   var $z01_telef = null; 
   var $z01_ident = null; 
   var $z01_login = 0; 
   var $z01_incest = null; 
   var $z01_telcel = null; 
   var $z01_email = null; 
   var $z01_endcon = null; 
   var $z01_numcon = 0; 
   var $z01_comcon = null; 
   var $z01_baicon = null; 
   var $z01_muncon = null; 
   var $z01_ufcon = null; 
   var $z01_cepcon = null; 
   var $z01_cxposcon = null; 
   var $z01_telcon = null; 
   var $z01_celcon = null; 
   var $z01_emailc = null; 
   var $z01_nacion = 0; 
   var $z01_estciv = 0; 
   var $z01_profis = null; 
   var $z01_tipcre = 0; 
   var $z01_cgccpf = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 z01_numcgm = int4 = Numcgm 
                 z01_nome = varchar(40) = Nome 
                 z01_ender = varchar(40) = Endereco 
                 z01_numero = int4 = Numero 
                 z01_compl = varchar(20) = Complemento 
                 z01_bairro = varchar(20) = Bairro 
                 z01_munic = varchar(20) = Municipio 
                 z01_uf = varchar(2) = UF 
                 z01_cep = varchar(8) = CEP 
                 z01_cxpostal = varchar(20) = Caixa Postal 
                 z01_cadast = date = Data do cadastramento 
                 z01_telef = varchar(12) = Telefone 
                 z01_ident = varchar(20) = Identidade 
                 z01_login = int4 = Login 
                 z01_incest = varchar(15) = Inscricao Estadual 
                 z01_telcel = varchar(12) = Celular 
                 z01_email = varchar(30) = email 
                 z01_endcon = varchar(40) = Endereco Comercial 
                 z01_numcon = int4 = Numero 
                 z01_comcon = varchar(20) = Complemento 
                 z01_baicon = varchar(20) = Bairro do endereco comercial 
                 z01_muncon = varchar(20) = Municipio Comercial 
                 z01_ufcon = varchar(2) = Estado Comercial 
                 z01_cepcon = varchar(8) = CEP do endereco comercial 
                 z01_cxposcon = varchar(20) = Caixa postal comercial 
                 z01_telcon = varchar(12) = Telefone comercial 
                 z01_celcon = varchar(12) = Celular comercial 
                 z01_emailc = varchar(30) = email comercial 
                 z01_nacion = int4 = Nacionalidade 
                 z01_estciv = int4 = Estado civil 
                 z01_profis = varchar(40) = Profissao 
                 z01_tipcre = int4 = Tipo de credor 
                 z01_cgccpf = varchar(14) = CNPJ/CPF 
                 ";
   //funcao construtor da classe 
   function cl_cgm() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cgm"); 
	 $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]['PHP_SELF']);
   }
   // funcao para inclusao
   function incluir ($z01_numcgm=null){ 
     if($this->z01_nome == null ){ 
       $this->erro_sql = " Campo z01_nome nao declarado.";
       return false;
     }
     if($this->z01_ender == null ){ 
       $this->erro_sql = " Campo z01_ender nao declarado.";
       return false;
     }
     if($this->z01_numero == null ){ 
       $this->z01_numero = "0";
     }
     if($this->z01_cadast == null ){ 
       $this->z01_cadast = "null";
     }
     if($this->z01_login == null ){ 
       $this->z01_login = "0";
     }
     if($this->z01_numcon == null ){ 
       $this->z01_numcon = "0";
     }
     if($this->z01_nacion == null ){ 
       $this->z01_nacion = "0";
     }
     if($this->z01_estciv == null ){ 
       $this->z01_estciv = "0";
     }
     if($this->z01_tipcre == null ){ 
       $this->z01_tipcre = "0";
     }
     $result = @pg_query("select nextval('cgm_z01_numcgm_seq')"); 
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Verifique o cadastro da sequencia: cgm_z01_numcgm_seq do campo: z01_numcgm"; 
       return false; 
     }
     $z01_numcgm = pg_result($result,0,0); 
     $result = @pg_query("insert into cgm(
                                       z01_numcgm 
                                      ,z01_nome 
                                      ,z01_ender 
                                      ,z01_numero 
                                      ,z01_compl 
                                      ,z01_bairro 
                                      ,z01_munic 
                                      ,z01_uf 
                                      ,z01_cep 
                                      ,z01_cxpostal 
                                      ,z01_cadast 
                                      ,z01_telef 
                                      ,z01_ident 
                                      ,z01_login 
                                      ,z01_incest 
                                      ,z01_telcel 
                                      ,z01_email 
                                      ,z01_endcon 
                                      ,z01_numcon 
                                      ,z01_comcon 
                                      ,z01_baicon 
                                      ,z01_muncon 
                                      ,z01_ufcon 
                                      ,z01_cepcon 
                                      ,z01_cxposcon 
                                      ,z01_telcon 
                                      ,z01_celcon 
                                      ,z01_emailc 
                                      ,z01_nacion 
                                      ,z01_estciv 
                                      ,z01_profis 
                                      ,z01_tipcre 
                                      ,z01_cgccpf 
                       )
                values (
                                $z01_numcgm 
                               ,'$this->z01_nome' 
                               ,'$this->z01_ender' 
                               ,$this->z01_numero 
                               ,'$this->z01_compl' 
                               ,'$this->z01_bairro' 
                               ,'$this->z01_munic' 
                               ,'$this->z01_uf' 
                               ,'$this->z01_cep' 
                               ,'$this->z01_cxpostal' 
                               ,'$this->z01_cadast' 
                               ,'$this->z01_telef' 
                               ,'$this->z01_ident' 
                               ,$this->z01_login 
                               ,'$this->z01_incest' 
                               ,'$this->z01_telcel' 
                               ,'$this->z01_email' 
                               ,'$this->z01_endcon' 
                               ,$this->z01_numcon 
                               ,'$this->z01_comcon' 
                               ,'$this->z01_baicon' 
                               ,'$this->z01_muncon' 
                               ,'$this->z01_ufcon' 
                               ,'$this->z01_cepcon' 
                               ,'$this->z01_cxposcon' 
                               ,'$this->z01_telcon' 
                               ,'$this->z01_celcon' 
                               ,'$this->z01_emailc' 
                               ,$this->z01_nacion 
                               ,$this->z01_estciv 
                               ,'$this->z01_profis' 
                               ,$this->z01_tipcre 
                               ,'$this->z01_cgccpf' 
                      )");
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro Geral de Contribuinte ($z01_numcgm) nao Incluído. Inclusao Abortada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       db_redireciona("db_erros.php?pagina_retorno=".$this->pagina_retorno."&db_erro=".$this->erro_msg);
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao Efetivada com Sucesso";
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     db_redireciona($this->pagina_retorno);
     return true;
   } 
   // funcao para alteracao
   function alterar ($z01_numcgm=null) { 
     $sql = " update cgm set ";
     $virgula = "";
     if($this->z01_numcgm != null ){ 
       $sql  .= $virgula." z01_numcgm = $this->z01_numcgm ";
       $virgula = ",";
     }
     if($this->z01_nome != null ){ 
       $sql  .= $virgula." z01_nome = '$this->z01_nome' ";
       $virgula = ",";
     }
     if($this->z01_ender != null ){ 
       $sql  .= $virgula." z01_ender = '$this->z01_ender' ";
       $virgula = ",";
     }
     if($this->z01_numero != null ){ 
       $sql  .= $virgula." z01_numero = $this->z01_numero ";
       $virgula = ",";
     }
     if($this->z01_compl != null ){ 
       $sql  .= $virgula." z01_compl = '$this->z01_compl' ";
       $virgula = ",";
     }
     if($this->z01_bairro != null ){ 
       $sql  .= $virgula." z01_bairro = '$this->z01_bairro' ";
       $virgula = ",";
     }
     if($this->z01_munic != null ){ 
       $sql  .= $virgula." z01_munic = '$this->z01_munic' ";
       $virgula = ",";
     }
     if($this->z01_uf != null ){ 
       $sql  .= $virgula." z01_uf = '$this->z01_uf' ";
       $virgula = ",";
     }
     if($this->z01_cep != null ){ 
       $sql  .= $virgula." z01_cep = '$this->z01_cep' ";
       $virgula = ",";
     }
     if($this->z01_cxpostal != null ){ 
       $sql  .= $virgula." z01_cxpostal = '$this->z01_cxpostal' ";
       $virgula = ",";
     }
     if($this->z01_cadast != null && $this->z01_cadast != "--"){ 
       $sql  .= $virgula." z01_cadast = '$this->z01_cadast' ";
       $virgula = ",";
     }     else{ 
       $sql  .= $virgula." z01_cadast = null ";
       $virgula = ",";
     }
     if($this->z01_telef != null ){ 
       $sql  .= $virgula." z01_telef = '$this->z01_telef' ";
       $virgula = ",";
     }
     if($this->z01_ident != null ){ 
       $sql  .= $virgula." z01_ident = '$this->z01_ident' ";
       $virgula = ",";
     }
     if($this->z01_login != null ){ 
       $sql  .= $virgula." z01_login = $this->z01_login ";
       $virgula = ",";
     }
     if($this->z01_incest != null ){ 
       $sql  .= $virgula." z01_incest = '$this->z01_incest' ";
       $virgula = ",";
     }
     if($this->z01_telcel != null ){ 
       $sql  .= $virgula." z01_telcel = '$this->z01_telcel' ";
       $virgula = ",";
     }
     if($this->z01_email != null ){ 
       $sql  .= $virgula." z01_email = '$this->z01_email' ";
       $virgula = ",";
     }
     if($this->z01_endcon != null ){ 
       $sql  .= $virgula." z01_endcon = '$this->z01_endcon' ";
       $virgula = ",";
     }
     if($this->z01_numcon != null ){ 
       $sql  .= $virgula." z01_numcon = $this->z01_numcon ";
       $virgula = ",";
     }
     if($this->z01_comcon != null ){ 
       $sql  .= $virgula." z01_comcon = '$this->z01_comcon' ";
       $virgula = ",";
     }
     if($this->z01_baicon != null ){ 
       $sql  .= $virgula." z01_baicon = '$this->z01_baicon' ";
       $virgula = ",";
     }
     if($this->z01_muncon != null ){ 
       $sql  .= $virgula." z01_muncon = '$this->z01_muncon' ";
       $virgula = ",";
     }
     if($this->z01_ufcon != null ){ 
       $sql  .= $virgula." z01_ufcon = '$this->z01_ufcon' ";
       $virgula = ",";
     }
     if($this->z01_cepcon != null ){ 
       $sql  .= $virgula." z01_cepcon = '$this->z01_cepcon' ";
       $virgula = ",";
     }
     if($this->z01_cxposcon != null ){ 
       $sql  .= $virgula." z01_cxposcon = '$this->z01_cxposcon' ";
       $virgula = ",";
     }
     if($this->z01_telcon != null ){ 
       $sql  .= $virgula." z01_telcon = '$this->z01_telcon' ";
       $virgula = ",";
     }
     if($this->z01_celcon != null ){ 
       $sql  .= $virgula." z01_celcon = '$this->z01_celcon' ";
       $virgula = ",";
     }
     if($this->z01_emailc != null ){ 
       $sql  .= $virgula." z01_emailc = '$this->z01_emailc' ";
       $virgula = ",";
     }
     if($this->z01_nacion != null ){ 
       $sql  .= $virgula." z01_nacion = $this->z01_nacion ";
       $virgula = ",";
     }
     if($this->z01_estciv != null ){ 
       $sql  .= $virgula." z01_estciv = $this->z01_estciv ";
       $virgula = ",";
     }
     if($this->z01_profis != null ){ 
       $sql  .= $virgula." z01_profis = '$this->z01_profis' ";
       $virgula = ",";
     }
     if($this->z01_tipcre != null ){ 
       $sql  .= $virgula." z01_tipcre = $this->z01_tipcre ";
       $virgula = ",";
     }
     if($this->z01_cgccpf != null ){ 
       $sql  .= $virgula." z01_cgccpf = '$this->z01_cgccpf' ";
       $virgula = ",";
     }
     $sql .= " where  z01_numcgm = $z01_numcgm
";
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro Geral de Contribuinte ($z01_numcgm) nao Alterado. Alteracao Abortada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
	   
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro Geral de Contribuinte ($z01_numcgm) nao foi Alterado. Alteracao Executada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteracao Efetivada com Sucesso";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($z01_numcgm=null) { 
     $result = @pg_exec(" delete from cgm
                    where  z01_numcgm = $z01_numcgm
                    ");
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro Geral de Contribuinte ($z01_numcgm) nao Excluído. Exclusão Abortada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro Geral de Contribuinte ($z01_numcgm) nao Encontrado. Exclusão não Efetuada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão Efetivada com Sucesso";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = @pg_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       return false;
     }
     $this->numrows = pg_numrows($result);
     return $result;
   }
   // funcao do sql 
   function sql_query ( $z01_numcgm=null,$campos="*",$ordem=null){ 
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
     $sql .= " from cgm ";
     if($z01_numcgm!=null ){
       $sql .= " where z01_numcgm = $z01_numcgm"; 
     } 
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
   function sqlCodnome ($codnome=0,$campos="*"){
    $sql = "
      select $campos
      from cgm
	";
    if (codnome != 0){
      $sql .= "
	  where z01_numcgm = $codnome
	  ";
	}
	return $sql;
  }
   function sqlnome ($nome="",$campos="*"){
    $nome = strtoupper($nome);
	$sql = "
      select $campos
      from cgm
	";
    if (nome !=""){
      $sql .= "
	    where z01_nome like '$nome%'
                   ";
	}
	return $sql;
  }
}
?>